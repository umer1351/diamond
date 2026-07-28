export function errorMessage(message){
    
    toastr.error(message, "Error", {
        showMethod: "slideDown",
        hideMethod: "slideUp",
        timeOut: 2e3,
    });

}

export function successMessage(message){
    
    toastr.success(message, "Success", {
        showMethod: "slideDown",
        hideMethod: "slideUp",
        timeOut: 2e3,
    });

}