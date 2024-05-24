// Ketika halaman sepenuhnya dimuat
// $(window).on("load", function () {
//   // Tunda selama 1.5 detik sebelum menyembunyikan overlay
//   setTimeout(function () {
//     $("#overlay").fadeOut(); // Menghilangkan overlay secara perlahan
//   }, 1500); // Waktu penundaan dalam milidetik (di sini: 1500 ms = 1.5 detik)
// });
// DataTables JS
$(document).ready(function () {
  var table = $("#example").DataTable({
    columnDefs: [
      {
        className: "dtr-control arrow-right",
        orderable: false,
        targets: -1,
      },
      {
        targets: "_all",
        defaultContent: "",
      },
    ],
    responsive: {
      details: {
        type: "column",
        target: -1,
      },
    },
    dom:
      "<'d-flex justify-content-between align-items-center mb-4'<B><f>>" +
      "<'row'<'col-md-12'tr>>" +
      "<'row'<'col-md-12 d-flex justify-content-end align-items-center gap-3'ipl>>",
    // lengthChange: false,
    buttons: [
      // {
      //   extend: "pageLength",
      // },
      {
        extend: "colvis", // Menambahkan tombol Column Visibility
        postfixButtons: ["colvisRestore"], // Menambahkan tombol untuk mengembalikan visibilitas kolom
        text: "Tampil Kolom",
      },
      {
        extend: "copy",
        exportOptions: {
          columns: ":not(:last-child)", // Mengabaikan kolom terakhir (Aksi)
        },
        text: "Salin",
      },
      {
        extend: "excel",
        exportOptions: {
          columns: ":not(:last-child)", // Mengabaikan kolom terakhir (Aksi)
        },
        text: "Excel",
      },
      {
        extend: "pdf",
        orientation: "landscape",
        pageSize: "LEGAL",
        exportOptions: {
          columns: ":not(:last-child)", // Mengabaikan kolom terakhir (Aksi)
        },
        text: "PDF",
      },
      {
        extend: "print",
        // autoPrint: false,
        exportOptions: {
          columns: ":not(:last-child)", // Mengabaikan kolom terakhir (Aksi)
        },
        text: "Cetak",
      },
    ],
    language: {
      sEmptyTable: "Tidak ada data yang tersedia pada tabel ini",
      sInfo: "Menampilkan _START_ hingga _END_ dari _TOTAL_ entri",
      sInfoEmpty: "Menampilkan 0 hingga 0 dari 0 entri",
      sInfoFiltered: "(disaring dari total entri _MAX_)",
      sInfoPostFix: "",
      sInfoThousands: ",",
      sLengthMenu: "_MENU_",
      sLoadingRecords: "Memuat...",
      sProcessing: "Sedang memproses...",
      sSearch: "",
      searchPlaceholder: "Cari data...",
      sZeroRecords: "Tidak ditemukan data yang cocok",
      oAria: {
        sSortAscending: ": aktifkan untuk mengurutkan kolom secara ascending",
        sSortDescending: ": aktifkan untuk mengurutkan kolom secara descending",
      },
    },
    lengthMenu: [
      [10, 25, 50, -1],
      [10, 25, 50, 100],
    ], // Menetapkan opsi panjang halaman
  });

  table.buttons().container().appendTo("#example_wrapper .col-md-8:eq(0)");
  // Menambahkan margin bawah ke elemen yang menampung tombol dan fitur pencarian
  $("#example_wrapper .row").css("margin-bottom", "20px");
});

// Modal Auto Focus
document.addEventListener("DOMContentLoaded", function () {
  var modals = document.querySelectorAll(".modal");
  modals.forEach(function (modal) {
    modal.addEventListener("shown.bs.modal", function () {
      var autoFocus = modal.querySelector(".auto-focus");
      if (autoFocus) {
        autoFocus.focus();
      }
    });
  });
});

// Print Paper Wrapper
function printContent() {
  // Ambil isi dari elemen dengan kelas paper-wrapper
  var content = document.querySelector(".paper-wrapper").innerHTML;

  // Simpan konten dalam sebuah jendela pop-up
  var printWindow = window.open("", "_blank");

  // Tulis konten ke dalam jendela pop-up
  printWindow.document.write(
    "<html><head><title>Cetak Dokumen</title></head><body>"
  );
  printWindow.document.write(content);
  printWindow.document.write("</body></html>");

  // Tutup pop-up setelah pencetakan selesai
  printWindow.document.close();
  printWindow.print();
}
