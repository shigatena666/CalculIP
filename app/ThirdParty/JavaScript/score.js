$('btn-success[type="submit"]').click(function() {
	if ($('#score').length) { // si score existe
		$.ajax({
				method: "POST",
				url: "ajaxGetScore.php",
				data: {userID: $("#userID").text(), nomExo: $("#nomExo")}
			}).done(function(msg) {
				$('#score').text(msg);
			});
	}
});
