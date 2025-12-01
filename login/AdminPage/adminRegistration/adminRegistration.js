document.addEventListener("DOMContentLoaded", () => {
  const statusDiv = document.getElementById("php-status");
  const status = statusDiv.dataset.status;

  if (status === "success") {
    Swal.fire({
      icon: 'success',
      title: 'Success!',
      text: 'Your registration was successful 🎉',
      confirmButtonColor: '#007BFF'
    }).then(() =>
        window.location.href = "../adminLogin/adminLogin.php");
  } else if (status === "error") {
    alert("Something went wrong. Please try again.");
  }
});