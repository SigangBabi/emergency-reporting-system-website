document.addEventListener("DOMContentLoaded", () => {
  const statusDiv = document.getElementById("php-status");
  const status = statusDiv ? statusDiv.dataset.status : '';
  console.log(status);

  if (status === "success") {
    Swal.fire({
      icon: 'success',
      title: 'Success!',
      text: 'Credentials Successfully Changed, please login again to proceed',
      confirmButtonColor: '#007BFF'
    }).then(() => window.location.href = "logout.php");
  } else if (status === "error") {
    alert("Something went wrong. Please try again.");
  };

});
