$(function(){
	$(".administration th button").click(function(){
		var idUsager = $(this).parents("table").data("idusager");
		var Etat = !$(this).data("usrblacklisted");

		window.location.href="administration.php?oper=user&id="+idUsager+"&state="+Etat;
	});

	$(".administration td button").click(function(){
		var idRoman = $(this).parent().data("idroman");
		var Etat = !$(this).data("noveldeleted");

		window.location.href="administration.php?oper=novel&id="+idRoman+"&state="+Etat;
	});
});