const sign_in_btn = document.querySelector("#sign-in-btn");
const sign_up_btn = document.querySelector("#sign-up-btn");
const container = document.querySelector(".container");
const logo = document.querySelector(".logo img"); // Ambil elemen logo
const logoText = document.querySelector(".logo h3"); // Ambil elemen teks logo

sign_up_btn.addEventListener("click", () => {
  container.classList.add("sign-up-mode");
  logo.style.opacity = "0"; // Kurangi opacity untuk memulai animasi
  setTimeout(() => {
    logo.src = "./img/logo1.png"; // Ganti gambar setelah animasi opacity selesai
    logo.style.opacity = "1"; // Kembalikan opacity untuk menampilkan gambar baru
  }, 500); // Sesuaikan waktu (500ms) agar sesuai dengan durasi transisi opacity

  logoText.style.color = "#53222A"; // Ubah warna teks menjadi merah
});

sign_in_btn.addEventListener("click", () => {
  container.classList.remove("sign-up-mode");
  logo.style.opacity = "0"; // Kurangi opacity untuk memulai animasi
  setTimeout(() => {
    logo.src = "./img/logo2.png"; // Ganti gambar setelah animasi opacity selesai
    logo.style.opacity = "1"; // Kembalikan opacity untuk menampilkan gambar baru
  }, 500); // Sesuaikan waktu (500ms) agar sesuai dengan durasi transisi opacity

  logoText.style.color = ""; // Kembalikan warna teks ke default
});
