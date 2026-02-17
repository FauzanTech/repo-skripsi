
<?= $this->extend('layout/layout'); ?>
<?= $this->section('content'); ?>
<style>
   .form-search {
    border-radius: 0!important;
    font-size: 20px;
    flex: 1 1 auto;
    min-width: 0; /* important for flexbox to allow shrinking */
    padding-right: 0.5rem;
    box-sizing: border-box;
  }
   .input-group {
    border: solid 0.5px #473D4B;
    background: #fff;
   }
   .input-group-prepend {
    color: #473D4B;
    font-size: 18px;
   }
   .input-group-text {
    color: #473D4B;
    font-size: 20px;
    background: transparent;
   }
   .search-more {
    border: none;
    outline: none;
    text-align: none;
    background: transparent;
    margin: 0.2rem auto;
    font-size: 9pt;
    display: flex;
    color:#3E25CE;
   }
   .search-more:hover {
    color:#5392F0;
   }
   .search-goup {
    display: flex;
    align-items: center;
    width: 70%;
    margin: 0 auto;
    gap: .5rem;
   }
   .search-box {
    border: solid 0.5px #473D4B;
    background: #fff;
    border-radius: 20px;
    display: flex;
    align-items: center;
    padding: .25rem .6rem;
    flex: 1 1 auto;
    min-width: 0;
   }
   .search-box .input-group-prepend { margin-right: .5rem; }
   .search-box .form-search { border: none; padding-left:0; }

   .search-goup button{
     flex: 0 0 auto;
     margin-left: 0;
     height: 44px;
   }
   /* ensure button icon is visible on default state */
   .btn-primary .mdi, #embed-search-btn .mdi {
     color: #fff !important;
   }
   .form-search::placeholder { color: #999; }
</style>
<div class="main-panel">
  <div class="content-wrapper">
    <div>
      <div class="search-goup ">
        <div class="search-box">
          <div class="input-group-prepend ">
            <i class="input-group-text border-0 mdi mdi-magnify"></i>
          </div>
          <input type="text" class="form-search" placeholder="Cari di repository ITH" value="<?php if (isset($search)) echo $search ?>" name="search"/>
        </div>
        <button class="btn btn-primary" id="embed-search-btn" title="Pencarian berbasis embedding"><i class="mdi mdi-robot"></i>&nbsp;Cari</button>
      </div>
      <button class="search-more" data-toggle="modal" data-target="#search-modal">Pencarian Lebih Lanjut</button>
    </div>
    <p id="search-keyword"></p>
    <div class="row" id="reference-list">

    </div>
  </div>
</div>
</div>

<div class="modal fade" id="search-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="card-title">Pencarian Lanjut</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="forms-sample" method="POST" action="/insert-new-reference" enctype="multipart/form-data">
        
            <div class="form-group">
              <label for="journal-name">Nama Penulis</label>
              <input type="text" name="author" class="form-control" placeholder="Nama Penulis">
            </div>
            <div class="form-group" style="text-align: center">
              atau
            </div>
             <div class="form-group">
                <label for="prodi">Program Studi</label>
                <select class="form-select" id="prodi" name="prodi">
                  <option value="" selected>Semua Prodi</option>  
                  <?php
                    foreach($prodi as $row) { ?>
                      <option value="<?=$row['prodi_id'] ?>" ><?=$row['prodi_name']?></option>
                  <?php  }
                  ?> ?>
                </select>
              </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="search-btn">Cari</button>
      </div>
    </div>
  </div>
</div>
<script>
  function seeDetail(referenceId) {
    window.location = '/reference-detail?id=' + referenceId;
  }

  $(document).ready(function() {
    getList();
    function getList() {
      since_year = $("input[name='since-year']").val();
      search = $("input[name='search']").val();
      year = since_year || $("input[name='year']:checked").val() || '';
      disciplines = $("input[name='disciplines[]']:checked").map(function(){return $(this).val();}).get();
      type = $("input[name='reference-type[]']:checked").map(function(){return $(this).val();}).get();
      prodi = $("#prodi").val();
      author = $("input[name='author']").val();

      page = <?= $page ?>;
      url = '/list-item?search='+ search +'&page='+page+'&year='+year+
        '&reference_type='+type+'&disciplines='+disciplines+'&prodi='+prodi+'&author='+author;

      $.get(url, function(res) {
        // terima respons HTML untuk list-item dan tampilkan
          $('#reference-list').html(res);
      });
    }
    $("input[type='checkbox']").change(getList);
    $("input[type='radio']").change(getList);
    $("#search-btn").click(function() {
      getList();
      prodi = $("#prodi option:selected").text();
      author = $("input[name='author']").val();
      let text = ''
      if (author !== '' && (prodi !== '' && prodi !== 'Semua Prodi')) {
        text = 'Pencarian berdasarkan penulis <strong>'+author+'</strong> atau program studi <strong>'+prodi+'</strong>';
      } else if (author !== '') {
        text = 'Pencarian berdasarkan penulis <strong>'+author+'</strong>';
      } else if (prodi !== '' && prodi !== 'Semua Prodi') {
        text = 'Pencarian berdasarkan program studi <strong>'+prodi+'</strong>';
      }
      $('#search-keyword').html(text);
      
    });
    $("input[name='search']").keydown(function(e) {
      if (e.key === 'Enter') {
        getList();
      }
    });

    $("input[name='search']").keyup(function() {
      search = $("input[name='search']").val();
      if (search.length > 4) {
        getList();
      }
    });
    $("input[name='since-year']").keyup(function() {
      since_year = $("input[name='since-year']").val();
      if (since_year.length === 4) {
        getList();
      }
    });

    // Tombol pencarian embedding — render hasil menggunakan markup komponen yang sama (bisa memanggil /list-item per hasil jika diperlukan)
    function escapeHtml(str) {
      return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
    }

    // Modal detail untuk hasil embedding (ditambahkan sekali untuk seluruh halaman)
    if ($('#embed-detail-modal').length === 0) {
      $('body').append('\n<div class="modal fade" id="embed-detail-modal" tabindex="-1" role="dialog" aria-hidden="true">\n  <div class="modal-dialog modal-lg" role="document">\n    <div class="modal-content">\n      <div class="modal-header d-flex align-items-center">\n        <div class="mr-3"><span class="badge badge-success">Jurnal</span></div>\n        <h5 class="modal-title" id="embed-detail-title">Judul</h5>\n        <button type="button" class="close ml-auto" data-dismiss="modal" aria-label="Close">\n          <span aria-hidden="true">&times;</span>\n        </button>\n      </div>\n      <div class="modal-body" id="embed-detail-body">\n        <div class="mb-2" id="embed-detail-authors" style="color:#6c757d;"></div>\n        <div id="embed-detail-journal" class="mb-2" style="font-weight:600;color:#2a2a2a"></div>\n        <hr />\n        <div id="embed-detail-abstract"></div>\n        <hr />\n        <div id="embed-detail-meta" style="color:#6c757d;"></div>\n      </div>\n      <div class="modal-footer">\n        <button type="button" class="btn btn-secondary" id="embed-detail-back">Kembali</button>\n        <button type="button" class="btn btn-warning" id="embed-detail-download">Download</button>\n      </div>\n    </div>\n  </div>\n</div>');
      // Tombol Kembali menutup modal (jangan menjalankan ulang pencarian atau mereset halaman)
      $('body').on('click', '#embed-detail-back', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $('#embed-detail-modal').modal('hide');
      });
    }

    $("#embed-search-btn").click(function() {
      const q = $("input[name='search']").val();
      if (!q || q.trim().length === 0) {
        alert('Masukkan kata kunci untuk pencarian embedding');
        return;
      }
      $('#reference-list').html('<div class="col-12">Mencari embedding... 🔎</div>');

      // tangkap filter tahun yang sedang dipilih (since-year atau radio tahun)
      const since_year = $("input[name='since-year']").val();
      const selected_year = since_year || $("input[name='year']:checked").val() || '';

      // tangkap tipe referensi yang dipilih
      const selected_types = $("input[name='reference-type[]']:checked").map(function(){return $(this).val();}).get().join(',');

      const embedUrl = '/search-embed?search=' + encodeURIComponent(q) + '&year=' + encodeURIComponent(selected_year) + '&reference_type=' + encodeURIComponent(selected_types);

      $.get(embedUrl, function(res) {
        const results = res.results || [];
        if (!results.length) {
          $('#reference-list').html('<div class="col-12">Tidak ada hasil dari layanan embedding.</div>');
          return;
        }

        // jika filter tahun diatur, hanya pertahankan hasil embedding dengan tahun >= selected_year
        const filteredResults = selected_year ? results.filter(function(r) {
          if (!r.year) return false;
          const ry = parseInt(r.year, 10);
          return !isNaN(ry) && ry >= parseInt(selected_year, 10);
        }) : results;

        if (filteredResults.length === 0) {
          $('#reference-list').html('<div class="col-12">Tidak ada hasil embedding yang cocok dengan filter tahun.</div>');
          return;
        }

        // kosongkan dan render setiap hasil embedding (diikuti oleh kecocokan DB jika ada via /list-item)
        $('#reference-list').html('');
        filteredResults.forEach(function(r, idx) {
          const title = r.title || ('#' + (r.doc_id || 'unknown'));
          const year = r.year || '';
          const fullAbstract = r.abstract || '';
          const abstractPreview = r.abstract_preview || (fullAbstract && fullAbstract.length > 250 ? fullAbstract.substring(0,250) + '...' : fullAbstract);

          const wrapper = $('<div class="col-12 mb-3"></div>');

          // Bangun elemen kartu sehingga abstrak penuh dapat dilampirkan sebagai atribut data jika tersedia
          const header = $('<div class="card" style="border-radius:8px;padding:0.8rem;"></div>');
          const body = $('<div class="card-body"></div>');

          // baris penerbit/jurnal: badge + nama jurnal (jika ada) dan badge repositori opsional saat terpetakan
          const journalName = r.journal_name || r.journal || r.publisher || '';
          let pubHtml = '<div class="d-flex align-items-center" style="margin-bottom:8px;">';
          pubHtml += '<div class="badge badge-success">Jurnal</div>';
          if (journalName) pubHtml += '<div style="margin-left:0.6rem;font-weight:600;color:#2a2a2a;">' + escapeHtml(journalName) + '</div>';
          pubHtml += '</div>';
          body.append(pubHtml);

          // baris judul dengan tombol 'Lihat Detail' opsional untuk kecocokan DB
          const titleRow = $('<div class="d-flex justify-content-between align-items-start"></div>');
          titleRow.append('<h5 class="card-title mb-1" style="margin:0;font-size:1.2rem;">' + escapeHtml(title) + '</h5>');

          body.append(titleRow);

          // penulis (jika disediakan oleh metadata embedding)
          if (r.authors || r.author) {
            body.append('<div class="author mt-1" style="color:#777;">' + escapeHtml(r.authors || r.author) + '</div>');
          } else {
            body.append('<div class="author mt-1" style="min-height:18px;color:#777;"></div>');
          }

          body.append('<p class="embed-abstract-short" style="margin-top:8px;">' + escapeHtml(abstractPreview) + '</p>');

          // baris keywords
          const kw = r.keywords || r.keyword || '';
          body.append('<div class="keywords" style="color:#444;margin-top:6px;">' + (kw ? ('Keywords: ' + escapeHtml(kw)) : '') + '</div>');

          // penulis ditampilkan di bawah judul jika tersedia (dari embedding atau DB)
          if (r.authors || r.author) {
            // ensure author shown above abstract
            // the author row insertion earlier covers it; keep for compatibility
          }

          header.append(body);

          // atribut data embed untuk digunakan saat kartu diklik; encode objek penuh agar fleksibel
          const embedData = encodeURIComponent(JSON.stringify({ title: title, year: year, abstract: fullAbstract, preview: abstractPreview, doc_id: r.doc_id || '', journal: journalName, doi: r.doi || '', keywords: kw, reference_id: r.reference_id || '' }));
          header.attr('data-embed', embedData);

          // jika backend menemukan kecocokan DB, tambahkan data-reference-id agar bisa langsung dinavigasi (buka di tab baru)
          if (r.reference_id) {
            header.attr('data-reference-id', r.reference_id);
            header.css('cursor', 'pointer');
          }

          // tambahkan wrapper
          wrapper.append(header);
          $('#reference-list').append(wrapper);

        });

        // Handler .use-title dihapus (tidak terpakai) // dipertahankan sebagai catatan

        // if a card has data-reference-id, open the reference detail page in a new tab when clicked
        $('#reference-list').on('click', '.card[data-reference-id]', function(e) {
          const refId = $(this).attr('data-reference-id');
          if (refId) {
            window.open('/reference-detail?id=' + refId, '_blank');
            return;
          }
        });



        // Modal detail sudah dibuat di atas; duplikasi dihapus

        // buka modal detail saat kartu embedding diklik (hanya untuk item tanpa pemetaan DB)
        $('#reference-list').on('click', '.card[data-embed]:not([data-reference-id])', function(e) {
          e.stopPropagation();
          const raw = $(this).attr('data-embed') || '';
          let info = {};
          try { info = raw ? JSON.parse(decodeURIComponent(raw)) : {}; } catch (err) { info = {}; }

          // isi field modal dengan data yang tersedia
          $('#embed-detail-title').text(info.title || '—');
          $('#embed-detail-authors').text(info.authors || '');
          $('#embed-detail-journal').text((info.journal ? (info.journal + (info.year ? ' • ' + info.year : '')) : (info.year ? 'Tahun: ' + info.year : '')));

          let abstractHtml = '';
          if (info.abstract) {
            abstractHtml = '<h6>Abstrak</h6><p>' + escapeHtml(info.abstract) + '</p>';
          } else if (info.preview) {
            abstractHtml = '<p>' + escapeHtml(info.preview) + '</p>';
          } else {
            abstractHtml = '<p><em>Tidak ada abstrak tersedia.</em></p>';
          }
          $('#embed-detail-abstract').html(abstractHtml);

          let meta = '';
          if (info.doi) meta += 'Doi: ' + escapeHtml(info.doi) + '<br/>';
          if (info.keywords) meta += 'Keywords: ' + escapeHtml(info.keywords) + '<br/>';
          if (info.year) meta += 'Tanggal Terbit: ' + escapeHtml(info.year) + '<br/>';
          $('#embed-detail-meta').html(meta);

          // tampilkan modal
          $('#embed-detail-modal').modal('show');
        });

      }).fail(function() {
        $('#reference-list').html('<div class="col-12">Terjadi kesalahan saat memanggil layanan pencarian embedding.</div>');
      });

      // Duplikat modal detail dihapus (modal sudah dibuat satu kali di atas)

      // tampilkan hasil contoh pada saat awal load jika kotak pencarian kosong
      const initialSearch = $("input[name='search']").val().trim();
      if (!initialSearch) {
        const sampleResults = [
          { doc_id: 's1', title: 'Pelatihan Pemanfaatan Teknologi Augmented Reality Dan Virtual Reality Sebagai Media Pembelajaran Inovatif Bagi Guru Di SMP Negeri 10 Parepare', year: '2023', authors: 'Abdullah', abstract: 'Kegiatan pengabdian masyarakat ini bertujuan untuk meningkatkan keterampilan guru dalam menggunakan teknologi AR dan VR sebagai media pembelajaran inovatif di kelas. Metode pelaksanaan pengabdian ini ...', abstract_preview: 'Kegiatan pengabdian masyarakat ini bertujuan untuk meningkatkan keterampilan guru dalam menggunakan teknologi AR dan VR sebagai media pembelajaran inovatif di kelas...', keywords: 'Augmented Reality; Virtual Reality; Media Pembelajaran; Assemblr Edu; Youtube VR.' },
          { doc_id: 's2', title: 'Pelatihan MIT App Inventor Sebagai Upaya Meningkatkan Kemampuan Berpikir Logis Siswa SMAN 4 Parepare', year: '2022', authors: 'Abdullah', abstract: 'Kegiatan pengabdian kepada masyarakat ini bertujuan untuk meningkatkan kemampuan berpikir logis siswa SMAN 4 Parepare dengan mengajarkan penerapan logika matematika dalam menyusun instruksi komputer ...', abstract_preview: 'Kegiatan pengabdian kepada masyarakat ini bertujuan untuk meningkatkan kemampuan berpikir logis siswa SMAN 4 Parepare dengan mengajarkan penerapan logika matematika dalam menyusun instruksi komputer...', keywords: 'Berpikir Logis; MIT App Inventor; Pelatihan.' }
        ];

        sampleResults.forEach(function(r) {
          const title = r.title || ('#' + (r.doc_id || 'unknown'));
          const year = r.year || '';
          const fullAbstract = r.abstract || '';
          const abstractPreview = r.abstract_preview || (fullAbstract && fullAbstract.length > 250 ? fullAbstract.substring(0,250) + '...' : fullAbstract);

          const wrapper = $('<div class="col-12 mb-3"></div>');
          const header = $('<div class="card" style="border-radius:8px;padding:0.8rem;"></div>');
          const body = $('<div class="card-body"></div>');

          const journalName = r.journal || '';
          let pubHtml = '<div class="d-flex align-items-center" style="margin-bottom:8px;">';
          pubHtml += '<div class="badge badge-success">Jurnal</div>';
          if (journalName) pubHtml += '<div style="margin-left:0.6rem;font-weight:600;color:#2a2a2a;">' + escapeHtml(journalName) + '</div>';
          pubHtml += '</div>';
          body.append(pubHtml);

          body.append('<h5 class="card-title">' + escapeHtml(title) + '</h5>');
          body.append('<div class="author" style="min-height:18px;color:#777;"></div>');
          body.append('<p class="embed-abstract-short">' + escapeHtml(abstractPreview) + '</p>');
          const kw = r.keywords || '';
          body.append('<div class="keywords">' + (kw ? ('Keywords: ' + escapeHtml(kw)) : '') + '</div>');
          header.append(body);

          const embedData = encodeURIComponent(JSON.stringify({ title: title, year: year, abstract: fullAbstract, preview: abstractPreview, doc_id: r.doc_id || '', journal: r.journal || '', doi: r.doi || '', keywords: kw }));
          header.attr('data-embed', embedData);

          wrapper.append(header);
          $('#reference-list').append(wrapper);
        });
      }
    });
  });
</script>
<?= $this->endSection('content'); ?>

