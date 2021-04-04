const flashData = $(".flash-data").data("flashdata");
const validationErrors = $(".validation-errors").data("validation");

if (flashData) {
	Swal.fire({
		title: "Berhasil!",
		text: "Berhasil menambahkan data bus baru.",
		icon: "success",
		showConfirmButton: false,
		timer: 1500,
	});
}

if (validationErrors) {
	Swal.fire({
		title: "Gagal!",
		text: validationErrors,
		icon: "warning",
	});
}

$(".tombol-logout").on("click", function (e) {
	e.preventDefault();

	const href = $(this).attr("href");

	Swal.fire({
		title: "Siap Logout?",
		text: "Silahkan pilih tombol Logout untuk keluar dari sistem.",
		icon: "question",
		showCancelButton: true,
		confirmButtonColor: "#3085d6",
		cancelButtonColor: "#d33",
		confirmButtonText: "Ya, Logout!",
		cancelButtonText: "Kembali",
	}).then((result) => {
		if (result.value) {
			document.location.href = href;
		}
	});
});
