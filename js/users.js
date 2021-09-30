$(document).ready(function(){
	$('#form_search').submit(function()
	{
		localStorage.query=$('#query').val();

	});
	$('#userid').val(localStorage.userid);

	$('#create_user_form').submit(function(e){
		e.preventDefault();
		$.ajax({
			type: "POST",
			url:"callbacks/users.php",
			data:$('#create_user_form').serialize(),
			success:function(data)
			{
				if(data =='1')
				{
					Swal.fire('User Created Successfully');
					window.location.href='users.php';
				}
				else if(data=='2')
				{
					Swal.fire('Email Already Exists');
					return false;
				}
				else
				{
					Swal.fire('Fail to Create');
					return false;
				}


			}


		});

	});


	//delete users
	$(document).on('click','.del_user',function()
	{


		var dataString="function="+"deleteuser"+"&id="+$(this).attr('id');
		bootbox.confirm("Are you sure want to delete this user ?", function(result){
			{
				if(result)
				{
					$.ajax({
						type: "POST",
						url:"callbacks/users.php",
						data:dataString,
						success:function(data)
						{
							if(data==1)
							{

								swal({
									title: "User Deleted Successfully",
									text: "",
									type: "success"
								}).then(function() {
									window.location = "users.php";
								});

							}
							else
							{
								swal({
									title: "Fail to Delete!",
									text: "",
									type: "danger"
								}).then(function() {
									window.location = "users.php";
								});
							}

						}
					});
				}
			}
		});

	});

	//edit users
	$(document).on('click','.edit_user',function()
	{
		localStorage.setuserid=$(this).attr('id');

		window.location.href='edit_user.php';



	});

	//new users
	$(document).on('click','.new-user-button',function()
	{
		window.location.href='new_user.php';

	});

	$('#reset_user').click(function()
	{
		window.location.href='users.php';
	});



	$('#update_user_form').submit(function(e){

		$('#userid').val(localStorage.setuserid);

		$.ajax({
			type: "POST",
			url:"callbacks/users.php",
			data:$('#update_user_form').serialize(),
			success:function(data)
			{
				if(data==1)
				{

					swal({
						title: "User Updated Successfully",
						text: "",
						type: "success"
					}).then(function() {
						window.location = "users.php";
					});
				}
				else
				{
					swal({
						title: "Fail to Update",
						text: "",
						type: "danger"
					}).then(function() {
						window.location = "edit_user.php";
					});

				}

			}


		});

		e.preventDefault();
	});




		$('#new_user_form').submit(function(e){

			$.ajax({
				type: "POST",
				url:"callbacks/users.php",
				data:$('#new_user_form').serialize(),
				success:function(data)
				{
					if(data==1)
					{

						swal({
							title: "User Updated Successfully",
							text: "",
							type: "success"
						}).then(function() {
							window.location = "users.php";
						});
					}
					else
					{
						swal({
							title: "Fail to Update",
							text: "",
							type: "danger"
						}).then(function() {
							window.location = "new_user.php";
						});

					}

				}


			});

			e.preventDefault();
		});


		$('#update_user_profile').submit(function(e){

			$.ajax({
				type: "POST",
				url:"callbacks/users.php",
				data:$('#update_user_profile').serialize(),
				success:function(data)
				{
					if(data==1)
					{

						swal({
							title: "User Updated Successfully",
							text: "",
							type: "success"
						}).then(function() {
							window.location = "profile.php";
						});
					}
					else
					{
						swal({
							title: "Fail to Update",
							text: "",
							type: "danger"
						}).then(function() {
							window.location = "profile.php";
						});

					}

				}


			});

			e.preventDefault();
		});

		$('#update_settings_form').submit(function(e){

			$.ajax({
				type: "POST",
				url:"callbacks/users.php",
				data:$('#update_settings_form').serialize(),
				success:function(data)
				{
					if(data==1)
					{

						swal({
							title: "User Updated Successfully",
							text: "",
							type: "success"
						}).then(function() {
							window.location = "profile.php";
						});
					}
					else
					{
						swal({
							title: "Fail to Update",
							text: "",
							type: "danger"
						}).then(function() {
							window.location = "profile.php";
						});

					}

				}


			});

			e.preventDefault();
		});




});
