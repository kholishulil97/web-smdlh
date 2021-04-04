<script>
    $(document).ready(function(){
        $('#add').click(function () {
            $('#insert').val("Insert");
            $('#insert_form')[0].reset();
        });
     $(document).on('click', '.edit_data', function(){  
          var employee_id = $(this).attr("id");
          $.ajax({
        url:"fetch.php",
    method:"POST",
               data:{employee_id:employee_id},
    dataType:"json",
               success:function(data){
        $('#name').val(data.name);
    $('#address').val(data.address);
    $('#gender').val(data.gender);
    $('#designation').val(data.designation);
    $('#age').val(data.age);
    $('#employee_id').val(data.id);
    $('#insert').val("Update");
    $('#add_data_Modal').modal('show');
}
});
});
     $('#insert_form').on("submit", function(event){
        event.preventDefault();
    if($('#name').val() == "")
          {
        alert("Name is required");
}
else if($('#address').val() == '')
          {
        alert("Address is required");
}
else if($('#designation').val() == '')
          {
        alert("Designation is required");
}
else if($('#age').val() == '')
          {
        alert("Age is required");
}
else
          {
        $.ajax({
            url: "insert.php",
            method: "POST",
            data: $('#insert_form').serialize(),
            beforeSend: function () {
                $('#insert').val("Inserting");
            },
            success: function (data) {
                $('#insert_form')[0].reset();
                $('#add_data_Modal').modal('hide');
                $('#employee_table').html(data);
            }
        });
}
});
     $(document).on('click', '.view_data', function(){  
          var employee_id = $(this).attr("id");
    if(employee_id != '')
          {
        $.ajax({
            url: "select.php",
            method: "POST",
            data: { employee_id: employee_id },
            success: function (data) {
                $('#employee_detail').html(data);
                $('#dataModal').modal('show');
            }
        });
}
});
});
</script>