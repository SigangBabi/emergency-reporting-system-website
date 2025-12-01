document.addEventListener("DOMContentLoaded", () => {

  const reportOptions = document.querySelectorAll('.report-container a, .top-option a, .bottom-option a');
  const reportDataEl = document.getElementById('report-data');
  const defaultName = reportDataEl?.dataset?.name ?? '';
  const defaultAddress = reportDataEl?.dataset?.address ?? '';
  const defaultNumber = reportDataEl?.dataset?.number ?? '';

  reportOptions.forEach(el => {
    el.addEventListener('click', function(e) {
      e.preventDefault();
      const rawType = (this.textContent || this.innerText || 'this').trim();
      const typeKey = rawType.toLowerCase();

      // For "Other" open the same report form but include an additional input
      if (typeKey === 'other' || typeKey.includes('other')) {
        showReportForm('Other', '');
      } else {
        showReportForm(rawType, '');
      }
    });
  });
  
  function showReportForm(emergencyType, otherEmergency) {
    // include an extra input for 'other emergency' when emergencyType is Other
    const otherInput = (emergencyType.toLowerCase() === 'other')
      ? `<input id="swal-other" class="swal2-input" placeholder="Specify the emergency (required)" value="${escapeHtml(otherEmergency)}">`
      : '';

    const formHtml =
      `<input id="swal-name" class="swal2-input" placeholder="Name" value="${escapeHtml(defaultName)}">` +
      `<input id="swal-location" class="swal2-input" placeholder="Incident Location" value="${escapeHtml(defaultAddress)}">` +
      `<input id="swal-mobile" class="swal2-input" placeholder="Mobile Number" value="${escapeHtml(defaultNumber)}">` +
      otherInput +
      `<textarea id="swal-short" class="swal2-textarea" placeholder="Short description (optional)"></textarea>` +
      `<input id="swal-photo" type="file" accept="image/*" class="swal2-file">`;

    Swal.fire({
      title: `Report: ${emergencyType}`,
      html: formHtml,
      focusConfirm: false,
      showCancelButton: true,
      confirmButtonText: 'Submit',
      preConfirm: () => {
        const name = document.getElementById('swal-name').value.trim();
        const location = document.getElementById('swal-location').value.trim();
        const mobile = document.getElementById('swal-mobile').value.trim();
        // when Other, ensure the extra field is provided
        let otherVal = '';
        if (emergencyType.toLowerCase() === 'other') {
          otherVal = (document.getElementById('swal-other')?.value || '').trim();
          if (!otherVal) {
            Swal.showValidationMessage('Please specify the other emergency');
            return false;
          }
        }

        if (!name) {
          Swal.showValidationMessage('Name is required');
          return false;
        }
        if (!location) {
          Swal.showValidationMessage('Incident location is required');
          return false;
        }
        if (!mobile) {
          Swal.showValidationMessage('Mobile number is required');
          return false;
        }
        return {
          name, location, mobile,
          short_desc: document.getElementById('swal-short').value.trim(),
          other_emergency: otherVal || otherEmergency,
          photoInput: document.getElementById('swal-photo')
        };
      }
    }).then((result) => {
      if (result.isConfirmed && result.value) {
        const fd = new FormData();
        fd.append('emergency_type', emergencyType);
        // use provided other_emergency if present
        fd.append('other_emergency', result.value.other_emergency || otherEmergency);
        fd.append('location', result.value.location);
        fd.append('reporter_name', result.value.name);
        fd.append('mobile_no', result.value.mobile);
        fd.append('short_desc', result.value.short_desc || '');
        // file (optional)
        const fileEl = result.value.photoInput;
        if (fileEl && fileEl.files && fileEl.files[0]) {
          fd.append('emer_photo', fileEl.files[0]);
        }
        // save address if user provided one when default was empty
        const saveAddress = (!defaultAddress || defaultAddress.trim() === '' || defaultAddress.toLowerCase().includes('not provided')) && result.value.location;
        fd.append('save_address', saveAddress ? '1' : '0');

        Swal.fire({ title: 'Submitting report...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

        fetch('submitReport.php', { method: 'POST', body: fd, credentials: 'same-origin' })
          .then(r => r.json())
          .then(data => {
            Swal.close();
            if (data && data.status === 'success') {
              Swal.fire({ icon: 'success', title: 'Report submitted', text: data.message || 'Responders will be notified.' });
            } else {
              Swal.fire({ icon: 'error', title: 'Submission failed', text: (data && data.message) ? data.message : 'An error occurred.' });
            }
          })
          .catch(err => {
            console.error(err);
            Swal.close();
            Swal.fire({ icon: 'error', title: 'Network error', text: 'Could not submit report. Please try again.' });
          });
      }
    });
   }

  // small helper: escape values inserted into html string
  function escapeHtml(str) {
    return String(str || '').replace(/[&<>"']/g, function(m){ return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]); });
  }

});