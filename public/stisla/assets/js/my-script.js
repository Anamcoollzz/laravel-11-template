function disableForm() {
  if ($('#formAction .form-control').length > 0) $('#formAction .form-control').attr('disabled', 'disabled');
  if ($('#formAction .custom-switch-input').length > 0) $('#formAction .custom-switch-input').attr('disabled', 'disabled');
  if ($('#formAction .selectgroup-input').length > 0) $('#formAction .selectgroup-input').attr('disabled', 'disabled');
  if ($('#formAction .colorinput-input').length > 0) $('#formAction .colorinput-input').attr('disabled', 'disabled');
  if ($('#formAction #formAreaButton').length > 0) $('#formAction #formAreaButton').hide();
  if ($('.summernote-simple').length > 0) $('.summernote-simple').summernote('disable');
  if ($('.summernote').length > 0) $('.summernote').summernote('disable');
}

function unique(array) {
  return array.filter(function (el, index, arr) {
    return index == arr.indexOf(el);
  });
}

function initDataTables() {
  // datatable
  if ($('#datatable').length > 0) {
    var $dtTbl = $('#datatable');
    var options = {
      language: {
        lengthMenu: 'Menampilkan _MENU_ baris data per halaman',
        zeroRecords: 'Tidak ada data',
        info: 'Menampilkan halaman _PAGE_ dari _PAGES_',
        infoFiltered: '(filtered from _MAX_ total records)',
        search: 'Pencarian',
        paginate: {
          previous: 'Sebelumnya',
          next: 'Selanjutnya',
        },
        buttons: {
          copySuccess: {
            1: '1 baris disalin ke papanklip',
            _: '%d baris disalin ke papanklip',
          },
          copyTitle: 'Salin ke papanklip',
        },
      },
    };

    if ($dtTbl.data('export') === true) {
      var title = $dtTbl.data('title') && $dtTbl.data('title').replace(' ', '_').toLowerCase();
      title = title ? title + '_export' : document.title;
      options['dom'] = 'lBfrtip';
      options['buttons'] = [
        {
          attr: { id: 'copyDtBtn' },
          extend: 'copy',
          text: 'Salin',
          className: 'btn-primary',
          title: title,
        },
        {
          attr: { id: 'csvDtBtn' },
          extend: 'csv',
          text: 'CSV',
          className: 'btn-success',
          title: title,
        },
        {
          attr: { id: 'excelDtBtn' },
          extend: 'excel',
          text: 'Excel',
          className: 'btn-success',
          title: title,
        },
        {
          attr: { id: 'pdfDtBtn' },
          extend: 'pdf',
          text: 'PDF',
          orientation: 'landscape',
          pageSize: 'Legal',
          className: 'btn-danger',
          title: title,
          customize: function (doc) {
            var colCount = new Array();
            $dtTbl.find('tbody tr:first-child td').each(function () {
              if ($(this).attr('colspan')) {
                for (var i = 1; i <= $(this).attr('colspan'); $i++) {
                  colCount.push('*');
                }
              } else {
                colCount.push('*');
              }
            });
            doc.content[1].table.widths = colCount;
          },
        },
        {
          attr: { id: 'printDtBtn' },
          extend: 'print',
          text: 'Cetak',
          className: 'btn-primary',
        },
        {
          attr: { id: 'jsonDtBtn' },
          text: 'JSON',
          className: 'btn btn-warning',
          action: function (e, dt, button, config) {
            var data = dt.buttons.exportData();

            $.fn.dataTable.fileSave(new Blob([JSON.stringify(data)]), title + '.json');
          },
        },
      ];
    }
    $dtTbl.DataTable(options);
  }

  // datatable yajra
  if ($('.yajra-datatable').length > 0) {
    var $dtTblYajra = $('.yajra-datatable');
    var options = {
      processing: true,
      serverSide: true,
      ajax: $dtTblYajra.data('ajax-url'),
      columns: JSON.parse($('#yajraColumns').val()),
      language: {
        lengthMenu: 'Menampilkan _MENU_ baris data per halaman',
        zeroRecords: 'Tidak ada data',
        info: 'Menampilkan halaman _PAGE_ dari _PAGES_',
        infoFiltered: '(filtered from _MAX_ total records)',
        search: 'Pencarian',
        paginate: {
          previous: 'Sebelumnya',
          next: 'Selanjutnya',
        },
        buttons: {
          copySuccess: {
            1: '1 baris disalin ke papanklip',
            _: '%d baris disalin ke papanklip',
          },
          copyTitle: 'Salin ke papanklip',
        },
      },
    };

    if ($dtTblYajra.data('export') === true) {
      var title = $dtTblYajra.data('title') && $dtTblYajra.data('title').replace(' ', '_').toLowerCase();
      title = title ? title + '_export' : document.title;
      options['dom'] = 'lBfrtip';
      options['buttons'] = [
        {
          attr: { id: 'copyDtBtn' },
          extend: 'copy',
          text: 'Salin',
          className: 'btn-primary',
          title: title,
        },
        {
          attr: { id: 'csvDtBtn' },
          extend: 'csv',
          text: 'CSV',
          className: 'btn-success',
          title: title,
        },
        {
          attr: { id: 'excelDtBtn' },
          extend: 'excel',
          text: 'Excel',
          className: 'btn-success',
          title: title,
        },
        {
          attr: { id: 'pdfDtBtn' },
          extend: 'pdf',
          text: 'PDF',
          orientation: 'landscape',
          pageSize: 'Legal',
          className: 'btn-danger',
          title: title,
          customize: function (doc) {
            var colCount = new Array();
            $dtTblYajra.find('tbody tr:first-child td').each(function () {
              if ($(this).attr('colspan')) {
                for (var i = 1; i <= $(this).attr('colspan'); $i++) {
                  colCount.push('*');
                }
              } else {
                colCount.push('*');
              }
            });
            doc.content[1].table.widths = colCount;
          },
        },
        {
          attr: { id: 'printDtBtn' },
          extend: 'print',
          text: 'Cetak',
          className: 'btn-primary',
        },
        {
          attr: { id: 'jsonDtBtn' },
          text: 'JSON',
          className: 'btn btn-warning',
          action: function (e, dt, button, config) {
            var data = dt.buttons.exportData();

            $.fn.dataTable.fileSave(new Blob([JSON.stringify(data)]), title + '.json');
          },
        },
      ];
    }
    window.$dtTblYajra = $dtTblYajra.DataTable(options);
  }
}

function reloadDataTable() {
  window.$dtTblYajra.ajax.reload();
}

$(document).ready(function () {
  if ($('.datatable').length > 0) $('.datatable').DataTable();

  if (window.innerWidth <= 425) {
    $('.btn-save-form').addClass('btn-block');
    $('.btn-reset-form').addClass('btn-block');
  }

  // check all hak akses
  function checkAll() {
    if ($('[data-toggle="checkall"]').length) {
      $('[data-toggle="checkall"]').each(function (index, item) {
        $(item).on('change', function () {
          var checkbox = $(item).parent().parent().next().find('input');
          if (this.checked) {
            checkbox.prop('checked', true);
          } else {
            checkbox.prop('checked', false);
          }
        });
      });
    }
  }

  checkAll();

  if ($('[data-toggle="checkallmodul"]').length) {
    $('[data-toggle="checkallmodul"]').each(function (index, item) {
      $(item).on('change', function () {
        if (this.checked) {
          $('[data-toggle="checkall"]').each(function (index2, item2) {
            $(item2).prop('checked', true);
          });
        } else {
          $('[data-toggle="checkall"]').each(function (index2, item2) {
            $(item2).prop('checked', false);
          });
        }
        $('[data-toggle="checkall"]').trigger('change');
      });
    });
  }

  // summernote
  if ($('[data-toggle="summernote"]').length) {
    $('[data-toggle="summernote"]').each(function (index, item) {
      $(item).summernote({
        height: 500,
      });
    });
  }

  // login page only
  if ($('#sapaan').length > 0) {
    var date = new Date();
    var jam = date.getHours();
    var menit = date.getMinutes();
    var pesan = '';
    if (jam >= 18) {
      if (menit >= 30) pesan = 'Selamat Malam';
      else pesan = 'Selamat Sore';
    } else if (jam >= 14) {
      pesan = 'Selamat Sore';
    } else if (jam >= 10) {
      pesan = 'Selamat Siang';
    } else if (jam >= 4) {
      pesan = 'Selamat Pagi';
    }
    $('#sapaan').html(pesan);
  }

  // show or hide password
  if ($('#password').length > 0) {
    var tombol_mata = $('#password').parents('.form-group');
    tombol_mata = tombol_mata.find('.ikon2');
    if (tombol_mata.length > 0) {
      tombol_mata.css({
        cursor: 'pointer',
      });
      tombol_mata.on('click', function () {
        if (tombol_mata.find('.fa-eye').length > 0) {
          tombol_mata.parents('.form-group').find('input').attr('type', 'text');
          tombol_mata.find('i').removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
          tombol_mata.parents('.form-group').find('input').attr('type', 'password');
          tombol_mata.find('i').addClass('fa-eye').removeClass('fa-eye-slash');
        }
      });
    }
  }

  initDataTables();

  // datepicker
  if ($('.datepicker').length > 0) {
    $('.datepicker').daterangepicker({
      autoUpdateInput: false, //disable default date
      singleDatePicker: true,
      showDropdowns: true,
    });

    $('.datepicker').on('apply.daterangepicker', function (ev, picker) {
      $(this).val(picker.startDate.format('YYYY-MM-DD'));
    });
  }

  // toggle sidebar mini
  window.sidebar_miniku = function () {
    var WINDOW_WIDTH = $(window).outerWidth();
    if (typeof SIDEBAR_MINI !== 'undefined' && SIDEBAR_MINI === true && WINDOW_WIDTH > 1024) {
      setTimeout(function () {
        $('[data-toggle="sidebar"]').trigger('click');
      }, 500);
    }
  };

  sidebar_miniku();
});

// delete action
function hapus(e, action_url) {
  e.preventDefault();
  swal({
    title: 'Anda yakin?',
    text: 'Sekali dihapus, data tidak akan kembali lagi!',
    icon: 'warning',
    buttons: true,
    dangerMode: true,
    buttons: {
      cancel: {
        text: 'Batal',
        value: null,
        visible: true,
        className: '',
        closeModal: true,
      },
      confirm: {
        text: 'Lanjutkan',
      },
    },
  }).then(function (willDelete) {
    if (willDelete) {
      $('#form-hapus').attr('action', action_url);
      document.getElementById('form-hapus').submit();
    } else {
      swal('Okay, tidak jadi');
    }
  });
}

function validateEmail(email) {
  var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(String(email).toLowerCase());
}

// form validation

$('form')
  .find('button[type="submit"]')
  .click(function (e) {
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').remove();
    $('.input-group-text').removeClass('border-danger');
    $('.select2-selection').css({ 'border-color': '#e4e6fc' });
    $('.select2-selection--single,.select2-selection--multiple').css({ 'border-color': '#e4e6fc' });
    $('label.text-danger').removeClass('text-danger');
    $('.error-checkbox').remove();
    var $button = $(this);
    var $form = $button.closest('form');
    $form.find('[data-toggle="summernote_message"]').empty();
    $form.find('.bootstrap-tagsinput').css({ 'border-color': '#e4e6fc' });
    var $formControl = $form.find('.form-control:not(.note-form-control)');
    var notError = true;
    $formControl.each(function (index, item) {
      // console.log('item', item);
      // console.log('itemvalue', item.value);
      if ($(item).hasClass('inputtags') && !item.value) {
        notError = false;
        $(item).parent().find('.bootstrap-tagsinput').css({ 'border-color': 'red' });
        $(item).addClass('is-invalid');
        var $formGroup = $(item).closest('.form-group');
        $formGroup.find('.input-group-text').addClass('border-danger');
        var $invalidFeedback = $formGroup.find('.invalid-feedback');
        if ($invalidFeedback.length <= 0) {
          $(item).after('<span class="invalid-feedback"></span>');
        }
        $invalidFeedback = $formGroup.find('.invalid-feedback');
        var label = $formGroup.find('label').text() || '';
        label = label.replaceAll('*', '');
        $invalidFeedback.html($(item).attr('required-message') || label + ' tidak boleh kosong');
        $formGroup.find('label').addClass('text-danger');
      } else if (item.hasAttribute('required') && !item.value && item.tagName === 'SELECT' && $(item).hasClass('select2')) {
        notError = false;
        $(item).addClass('is-invalid');
        var $formGroup = $(item).closest('.form-group');
        $formGroup.find('label').addClass('text-danger');
        $formGroup.find('.select2-selection').css({ 'border-color': 'red' });
        var $invalidFeedback = $formGroup.find('.invalid-feedback');
        if ($invalidFeedback.length <= 0) {
          $formGroup.append('<span class="invalid-feedback"></span>');
        }
        $invalidFeedback = $formGroup.find('.invalid-feedback');
        $invalidFeedback.html($(item).attr('required-message') || 'Tidak boleh kosong');
        $formGroup.find('.input-group-text').addClass('border-danger');
        var label = $formGroup.find('label').text() || '';
        label = label.replaceAll('*', '');
        $invalidFeedback.html($(item).attr('required-message') || label + ' tidak boleh kosong');
      } else if (item.hasAttribute('required') && !item.value) {
        notError = false;
        $(item).addClass('is-invalid');
        var $formGroup = $(item).closest('.form-group');
        $formGroup.find('label').addClass('text-danger');
        var $invalidFeedback = $formGroup.find('.invalid-feedback');
        if ($(item).parent().hasClass('colorpickerinput')) {
          if ($invalidFeedback.length <= 0) {
            $(item).parent().after('<span class="invalid-feedback" style="display: block"></span>');
          }
        } else {
          if ($invalidFeedback.length <= 0) {
            $(item).after('<span class="invalid-feedback"></span>');
          }
        }
        $invalidFeedback = $formGroup.find('.invalid-feedback');
        $invalidFeedback.html($(item).attr('required-message') || 'Tidak boleh kosong');
        $formGroup.find('.input-group-text').addClass('border-danger');
        var label = $formGroup.find('label').text() || '';
        label = label.replaceAll('*', '');
        $invalidFeedback.html($(item).attr('required-message') || label + ' tidak boleh kosong');
      } else if (item.type == 'email') {
        if (!validateEmail(item.value)) {
          notError = false;
          $(item).addClass('is-invalid');
          var $formGroup = $(item).closest('.form-group');
          $formGroup.find('.input-group-text').addClass('border-danger');
          var $invalidFeedback = $formGroup.find('.invalid-feedback');
          if ($invalidFeedback.length <= 0) {
            $(item).after('<span class="invalid-feedback"></span>');
          }
          $invalidFeedback = $formGroup.find('.invalid-feedback');
          $invalidFeedback.html($(item).attr('email-message') || 'Email tidak valid');
          $formGroup.find('label').addClass('text-danger');
        }
      } else if ($(item).hasClass('select2') && !item.value) {
        $(item).parent().find('label').addClass('text-danger');
        $(item).parent().find('.select2-selection--single,.select2-selection--multiple').css({ 'border-color': 'red' });
      }
    });

    // summernote
    $summernote = $form.find('[data-toggle="summernote"]');
    if ($summernote.length) {
      var summernote = $summernote[0];
      if (summernote.hasAttribute('required-message') && $summernote.summernote('code') == '<p><br></p>') {
        $form.find('[data-toggle="summernote_message"]').html($summernote.attr('required-message'));
      }
      $summernote.before().val($summernote.summernote('code'));
    }

    // summernote new
    var $summernoteNew = $form.find('.summernote');
    if ($summernoteNew.length) {
      $summernoteNew.each(function (index, item) {
        var $item = $(item);
        if (item.hasAttribute('required') && ($item.summernote('code') == '<p><br></p>' || $item.summernote('code') == '')) {
          $item.parent().find('label').addClass('text-danger');
          var label = $item.parent().find('label').text().replaceAll('*', '');
          $form.find('[data-toggle="summernote_message"]').html($item.attr('required-message'));
          $item.next().after('<div class="invalid-feedback" style="display: block; margin-top: -20px;">' + label + ' tidak boleh kosong' + '</div>');
        }
        $item.before().val($item.summernote('code'));
      });
    }

    // summernote simple
    var $summernoteSimple = $form.find('.summernote-simple');
    if ($summernoteSimple.length) {
      $summernoteSimple.each(function (index, item) {
        var $item = $(item);
        if (item.hasAttribute('required') && ($item.summernote('code') == '<p><br></p>' || $item.summernote('code') == '')) {
          $item.parent().find('label').addClass('text-danger');
          var label = $item.parent().find('label').text().replaceAll('*', '');
          $form.find('[data-toggle="summernote_message"]').html($item.attr('required-message'));
          $item.next().after('<div class="invalid-feedback" style="display: block; margin-top: -20px;">' + label + ' tidak boleh kosong' + '</div>');
        }
        $item.before().val($item.summernote('code'));
      });
    }

    // radio
    var radioNames = [];
    $('.custom-switch-input').each(function (index, item) {
      radioNames.push({ name: item.name, required: item.required });
    });
    radioNames = radioNames
      .filter(function (item) {
        return item.required;
      })
      .map(function (item) {
        return item.name;
      });
    radioNames = unique(radioNames);
    radioNames.forEach(function (item) {
      if (!$('input[name="' + item + '"]:checked').val()) {
        notError = false;
        $('input[name="' + item + '"]')
          .parent()
          .parent()
          .parent()
          .find('label')
          .addClass('text-danger');
        var label = $('input[name="' + item + '"]')
          .parent()
          .parent()
          .parent()
          .find('label:first')
          .text()
          .replaceAll('*', '');
        $('input[name="' + item + '"]')
          .parent()
          .parent()
          .after('<div class="invalid-feedback error-checkbox" style="display: block;">' + label + ' tidak boleh kosong</div>');
      }
    });

    // checkbox
    var checkboxNames = [];
    $('.selectgroup-input').each(function (index, item) {
      checkboxNames.push({ name: item.name, required: $(item).parent().parent().parent().data('required') == 1 });
    });
    checkboxNames = checkboxNames
      .filter(function (item) {
        return item.required;
      })
      .map(function (item) {
        return item.name;
      });
    checkboxNames = unique(checkboxNames);
    checkboxNames.forEach(function (item) {
      if (!$('input[name="' + item + '"]:checked').val()) {
        notError = false;
        $('input[name="' + item + '"]')
          .parent()
          .parent()
          .parent()
          .find('label.form-label')
          .addClass('text-danger');
        var label = $('input[name="' + item + '"]')
          .parent()
          .parent()
          .parent()
          .find('label.form-label')
          .text()
          .replaceAll('*', '');
        $('input[name="' + item + '"]')
          .parent()
          .parent()
          .after('<div class="invalid-feedback error-checkbox" style="display: block;">' + label + ' tidak boleh kosong</div>');
      }
    });

    // checkbox2
    var checkboxNames2 = [];
    $('.colorinput-input').each(function (index, item) {
      checkboxNames2.push({ name: item.name, required: $(item).parent().parent().parent().parent().data('required') == 1 });
    });
    checkboxNames2 = checkboxNames2
      .filter(function (item) {
        return item.required;
      })
      .map(function (item) {
        return item.name;
      });
    checkboxNames2 = unique(checkboxNames2);
    checkboxNames2.forEach(function (item) {
      if (!$('input[name="' + item + '"]:checked').val()) {
        notError = false;
        $('input[name="' + item + '"]')
          .parent()
          .parent()
          .parent()
          .parent()
          .parent()
          .find('label.form-label')
          .addClass('text-danger');
        var label = $('input[name="' + item + '"]')
          .parent()
          .parent()
          .parent()
          .parent()
          .parent()
          .find('label.form-label')
          .text()
          .replaceAll('*', '');
        $('input[name="' + item + '"]')
          .parent()
          .parent()
          .parent()
          .parent()
          .after('<div class="invalid-feedback error-checkbox" style="display: block; margin-top: -25px; margin-bottom: 10px;">' + label + ' tidak boleh kosong</div>');
      }
    });

    if (!notError) {
      $('html, body').animate(
        {
          scrollTop: $form.offset().top - 100,
        },
        1000
      );
      errorMsg('Ada yang error pada form, silakan cek kembali!!!');
    }
    // return false;
    return notError;
  });

// swal message

function errorMsg(msg) {
  swal('Error!!!', msg, 'error');
}

function successMsg(msg) {
  return swal('Sukses', msg, 'success');
}

function showImportModal(e) {
  e.preventDefault();
  $('#importModal').modal('show');
}

function showModalForm(e, action, link) {
  e.preventDefault();
  window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
  $('#modalForm').parent().attr('action', link.replaceAll('/edit', '').replaceAll('/create', ''));
  $('#modalForm').modal('show');
  $('#modalForm').find('.modal-body').html('memproses...');
  $('#modalForm').find('.modal-footer').hide();

  var title = 'Tambah Data';
  if (action === 'edit') title = 'Ubah Data';
  else if (action === 'detail') title = 'Detail Data';

  $('#modalForm').find('.modal-title').html(title);
  window.axios.get(link).then(function (response) {
    setTimeout(
      function () {
        $('#modalForm').find('.modal-body').html(response.data);

        if (action === 'detail') disableForm();
        // test purpose
        // $('[name="text"]').val('halo');
        // $('[name="number"]').val('1');
        // $('#currency').val('1400');
        // $('#currency_idr').val('1500');
        // $('[name="date"]').val('1998-04-08');
        // $('[name="time"]').val('10:10');
        // $('[name="color"]').val('#888888');
        // $('[name="textarea"]').val('1998-04-08');
        // $('[name="summernote"]').val('1998-04-08');
        // $('[name="summernote_simple"]').val('1998-04-08');
        // $('[name="tags"]').val('1998-04-08');

        if (action === 'detail') {
        } else $('#modalForm').find('.modal-footer').show();
        initForm();
      },
      action === 'create' ? 500 : 1000
    );
  });
}

if ($('#sessionSuccessMessage').val()) swal('Sukses', $('#sessionSuccessMessage').val(), 'success');

if ($('#sessionErrorMessage').val()) swal('Gagal', $('#sessionErrorMessage').val(), 'error');

function deleteGlobal(e, action_url) {
  e.preventDefault();
  swal({
    title: 'Anda yakin?',
    text: 'Sekali dihapus, data tidak akan kembali lagi!',
    icon: 'warning',
    buttons: true,
    dangerMode: true,
    buttons: {
      cancel: {
        text: 'Batal',
        value: null,
        visible: true,
        className: '',
        closeModal: true,
      },
      confirm: {
        text: 'Lanjutkan',
      },
    },
  }).then(function (willDelete) {
    if (willDelete) {
      if ($('#isAjax').val() == 1 || $('#isAjaxYajra').val() == 1) {
        swal('Info', 'Sedang memproses...', 'info');
        window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        window.axios
          .delete(action_url)
          .then(function (response) {
            if ($('#isAjaxYajra').val() == 1) {
              reloadDataTable();
            } else {
              getData();
            }
            successMsg(response.data.message).then(function () {});
          })
          .catch(function (error) {});
      } else {
        $('#formDeleteGlobal').attr('action', action_url);
        document.getElementById('formDeleteGlobal').submit();
      }
    } else {
      swal('Info', 'Okay, tidak jadi', 'info');
    }
  });
}

function initSummernote() {
  if (jQuery().summernote) {
    if ($('.summernote').length > 0)
      $('.summernote').summernote({
        dialogsInBody: true,
        minHeight: 250,
      });

    if ($('.summernote-simple').length > 0)
      $('.summernote-simple').summernote({
        dialogsInBody: true,
        minHeight: 150,
        toolbar: [
          ['style', ['bold', 'italic', 'underline', 'clear']],
          ['font', ['strikethrough']],
          ['para', ['paragraph']],
        ],
      });
  }
}

function initSelect2() {
  if ($('.select2').length > 0) {
    setTimeout(function () {
      $('.select2').each(function (index, item) {
        $(item).select2();
      });
    }, 1000);
  }
}

function initColorPicker() {
  if ($('.colorpickerinput').length > 0)
    $('.colorpickerinput').colorpicker({
      format: 'hex',
      component: '.input-group-append',
    });
}

function initCleave() {
  if ($('.currency').length > 0)
    new Cleave('.currency', {
      numeral: true,
      numeralThousandsGroupStyle: 'thousand',
    });

  if ($('.currency_idr').length > 0)
    new Cleave('.currency_idr', {
      numeral: true,
      numeralDecimalMark: ',',
      delimiter: '.',
    });
}

function initTags() {
  if ($('.inputtags').length > 0) {
    setTimeout(function () {
      $('.inputtags').tagsinput('items');
    }, 1000);
  }
}

function getData() {
  window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
  window.axios
    .get(window.location.href)
    .then(function (response) {
      $('#datatable-view').html(response.data.data);
      initDataTables();
      $('#modalForm').modal('hide');
    })
    .catch(function (error) {});
}

function onSubmitForm(e) {
  e.preventDefault();
  var form = e.target;
  var formData = new FormData(form);
  var action = form.getAttribute('action');
  $('#modalForm').find('button[type="submit"]').attr('disabled', true).html('Menyimpan...');
  window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
  window.axios
    .post(action, formData)
    .then(function (response) {
      $('#modalForm').find('button[type="submit"]').attr('disabled', false).html('Simpan');
      successMsg(response.data.message).then(function () {
        // window.location.reload();
        if ($('#isAjaxYajra').val() === '1') {
          setTimeout(function () {
            reloadDataTable();
          }, 1000);
          $('#modalForm').modal('hide');
        } else {
          getData();
        }
      });
    })
    .catch(function (error) {
      swal('Gagal', error.response.data.message, 'error');
    });
}

// summernote
$(function () {
  initSummernote();
  initSelect2();
  initColorPicker();
  initCleave();
  initTags();
});

function initForm() {
  initSummernote();
  initSelect2();
  initColorPicker();
  initCleave();
  initTags();
}
