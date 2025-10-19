document.addEventListener("DOMContentLoaded", () => {
  const statusDiv = document.getElementById("php-status");
  const status = statusDiv ? statusDiv.dataset.status : '';
  console.log(status);

  if (status === "success") {
    Swal.fire({
      icon: 'success',
      title: 'Success!',
      text: 'Information Updated Successfully 🎉',
      confirmButtonColor: '#007BFF'
    }).then(() => window.location.href = "Userdashboard.php");
  } else if (status === "error") {
    alert("Something went wrong. Please try again.");
  };

});
