document.addEventListener("DOMContentLoaded", () => {

  const reportOptions = document.querySelectorAll('.report-container a, .top-option a, .bottom-option a');
  const reportDataEl = document.getElementById('report-data');
  const defaultName = reportDataEl?.dataset?.name ?? '';
  const defaultAddress = reportDataEl?.dataset?.address ?? '';

  reportOptions.forEach(el => {
    el.addEventListener('click', function(e) {
      e.preventDefault();
      const rawType = (this.textContent || this.innerText || 'this').trim();
      const typeKey = rawType.toLowerCase();

      if (typeKey === 'other' || typeKey.includes('other')) {
        Swal.fire({
          title: 'Specify other emergency',
          input: 'text',
          inputPlaceholder: 'Describe the emergency (e.g. lost child, gas leak...)',
          inputAttributes: { maxlength: 200 },
          showCancelButton: true,
          confirmButtonText: 'Next',
          preConfirm: (value) => {
            if (!value || !value.trim()) {
              Swal.showValidationMessage('Please describe the emergency');
            } else if (value.trim().length < 3) {
              Swal.showValidationMessage('Please provide a more descriptive text');
            } else {
              return value.trim();
            }
          }
        }).then((res) => {
          if (res.isConfirmed && res.value) {
            const otherEmergency = res.value;
            // proceed to address check -> confirmation -> submit
            ensureAddressThenConfirmAndSubmit('Other', otherEmergency);
          }
        });
      } else {
        ensureAddressThenConfirmAndSubmit(rawType, '');
      }
    });
  });

  // If user has no saved address, ask for it first. Then call confirmAndSubmit.
  function ensureAddressThenConfirmAndSubmit(emergencyType, otherEmergency) {
    const noAddress = !defaultAddress || defaultAddress.trim() === '' || defaultAddress.toLowerCase().includes('not provided');

    if (noAddress) {
      Swal.fire({
        title: 'Provide your address',
        input: 'text',
        inputPlaceholder: 'Enter your full address (street, barangay, city...)',
        inputAttributes: { maxlength: 255 },
        showCancelButton: true,
        confirmButtonText: 'Continue',
        preConfirm: (value) => {
          if (!value || !value.trim() || value.trim().length < 5) {
            Swal.showValidationMessage('Please provide a valid address (at least 5 characters)');
          } else {
            return value.trim();
          }
        }
      }).then((addrRes) => {
        if (addrRes.isConfirmed && addrRes.value) {
          // pass the provided address and mark that it should be saved
          confirmAndSubmit(emergencyType, otherEmergency, addrRes.value, true);
        }
      });
    } else {
      // use existing address (do not mark to save)
      confirmAndSubmit(emergencyType, otherEmergency, defaultAddress, false);
    }
  }

  function confirmAndSubmit(emergencyType, otherEmergency, location, saveAddress) {
    const msg = emergencyType.toLowerCase() === 'other'
      ? `You entered: "${otherEmergency}". Location: "${location}". Once submitted, responders will be coming to your location in response to this emergency. Do you want to continue?`
      : `Location: "${location}". Once submitted, responders will be coming to your location in response to your emergency. Do you want to continue?`;

    Swal.fire({
      title: `Submit ${emergencyType} report?`,
      text: msg,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Submit',
      cancelButtonText: 'Cancel',
      focusCancel: true
    }).then((result) => {
      if (result.isConfirmed) {
        const payload = new FormData();
        payload.append('emergency_type', emergencyType);
        payload.append('location', location);
        payload.append('other_emergency', otherEmergency);
        payload.append('save_address', saveAddress ? '1' : '0');

        Swal.fire({
          title: 'Submitting report...',
          allowOutsideClick: false,
          didOpen: () => Swal.showLoading()
        });

        fetch('submitReport.php', {
          method: 'POST',
          body: payload,
          credentials: 'same-origin'
        })
        .then(r => r.json())
        .then(data => {
          Swal.close();
          if (data && data.status === 'success') {
            Swal.fire({
              icon: 'success',
              title: 'Report submitted',
              text: data.message || 'Responders will be notified and will try their best to reach you.'
            }).then(() => {
              // optionally refresh page or update UI
              if (saveAddress) {
                // update local defaultAddress so subsequent submits don't re-prompt
                // Note: this only updates the variable in this runtime
                // a page reload would reflect DB-saved address
                // eslint-disable-next-line no-unused-expressions
                (function(){ /* no-op */ })();
              }
            });
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Submission failed',
              text: (data && data.message) ? data.message : 'An error occurred while submitting your report.'
            });
          }
        })
        .catch(err => {
          console.error(err);
          Swal.close();
          Swal.fire({
            icon: 'error',
            title: 'Network error',
            text: 'Could not submit report. Please try again.'
          });
        });
      }
    });
  }

});