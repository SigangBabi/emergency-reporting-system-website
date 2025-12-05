
    // handle status change
    document.addEventListener('DOMContentLoaded', function(){
      document.querySelectorAll('.status-select').forEach(function(sel){
        sel.addEventListener('change', function(){
          var id = this.dataset.id;
          var status = this.value;
          fetch('usersList.php', {
            method: 'POST',
            headers: {'Accept':'application/json'},
            body: new URLSearchParams({ id: id, status: status })
          })
          .then(r=>r.json())
          .then(function(j){
            if(j.status !== 'success'){
              alert('Failed to update status: ' + (j.message||''));
            }
          })
          .catch(function(err){
            console.error(err);
            alert('Network error while updating status');
          });
        });
      });

      document.querySelectorAll('.delete-user').forEach(function(btn){
        btn.addEventListener('click', function(){
          if(!confirm('Delete this user? This action cannot be undone.')) return;
          var id = this.dataset.id;
          fetch('deleteUser.php', {
            method: 'POST',
            headers: {'Accept':'application/json'},
            body: new URLSearchParams({ id: id })
          })
          .then(r => r.json())
          .then(function(j){
            if(j.status === 'success'){
              var row = document.getElementById('report-row-' + id);
              if(row) row.remove();
            } else {
              alert('Failed to delete: ' + (j.message||''));
            }
          })
          .catch(function(err){
            console.error(err);
            alert('Network error while deleting');
          });
        });
      });
    });
