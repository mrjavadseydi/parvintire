<script>

    $('.select2').select2({
        dir: "rtl",
        closeOnSelect: false,
        onSelect: function () {

        }
    });

   @if(!isset($_GET['filters']))
       $('.filters').hide();
   @endif

</script>
