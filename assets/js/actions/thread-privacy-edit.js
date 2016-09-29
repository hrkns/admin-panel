function __action(){
	/*
		call function to exec request to change the privacy of the thread
	*/
	$("#form_thread-privacy_edit").submit(function(e){
		e.preventDefault();
		$("#modal_thread-privacy_edit").modal("hide");
		Section.save_thread_after_confirmation();
	});
}