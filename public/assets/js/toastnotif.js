function showToast(toastrType, toastrMessage) {
    toastr.options = {
        "progressBar": true,
        "closeButton": true,
    };

    switch (toastrType) {
        case 'success':
            toastr.success(toastrMessage);
            break;
        case 'error':
            toastr.error(toastrMessage);
            break;
        case 'warning':
            toastr.warning(toastrMessage);
            break;
        case 'info':
        default:
            toastr.info(toastrMessage);
            break;
    }
}