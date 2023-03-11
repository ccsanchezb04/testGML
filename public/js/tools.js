$('.onlyNumber').on('input', function() {
    this.value = this.value.replace(/[^0-9]/g, '');
});

$('.onlyText').on('input', function() {
    this.value = this.value.replace(/[^a-zA-Z ]/g, '');
});