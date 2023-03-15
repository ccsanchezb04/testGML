function formValidate(selector) {
    $(selector).find('.msm').remove();

    let flag = true;
    $.each($(selector).find('[required]'), function (indexInArray, valueOfElement) {
        let props = valueOfElement.attributes;
        let elementValue = $(valueOfElement).val();
        let msm = {};

        if (props.hasOwnProperty('minlength')) {
            let valMinlength = props.minlength.value;

            if (elementValue.length < parseInt(valMinlength)) {
                flag = false;
                msm.miminlength = "Caracteres mínimos: " + valMinlength;
            }
        }

        if (props.hasOwnProperty('maxlength')) {
            let valMaxlength = props.maxlength.value;

            if (elementValue.length > parseInt(valMaxlength)) {
                flag = false;
                msm.miminlength = "Caracteres máximos: " + valMaxlength;
            }
        }

        if (props.hasOwnProperty('min')) {
            let valMin = props.min.value;

            if (parseInt(valMin) < elementValue.length) {
                flag = false;
                msm.miminlength = "Min value: " + valMin;
            }
        }

        if (props.hasOwnProperty('max')) {
            let valMax = props.max.value;

            if (elementValue.length > parseInt(valMax)) {
                flag = false;
                msm.miminlength = "Max value: " + valMax;
            }
        }

        if (props.hasOwnProperty('type')) {
            if (props.type.value == "radio") {
                let validateCheked = $('.custom-control-input').is(':checked');
                if (!validateCheked) {
                    flag = false;
                    msm.uncheckedField = 'Required';
                }
            }

            if (props.type.value == "email") {
                var testEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
                if (!testEmail.test(elementValue)){
                    flag = false;
                    msm.emailIncorrect = 'Formato erroneo';
                } 
            }
        }

        if (elementValue == '' || elementValue == null) {
            flag = false;
            msm.emptyField = "Campo requerido";
        }

        let html = '';

        if (flag) {
            html = '<div class="msm valid-feedback" style="font-size: 15px;">¡Está bien!</div>';
        } else {
            let optios = '';
            $.each(msm, function (indexMsm, valueMsm) {
                optios += '<li>' + valueMsm + '</li>';
            });
            html = '<div id="divError" class="msm errorMessage" style="font-size: 13px; color:red;"><ul>' + optios + '</ul></div>'; // personalizar div del aviso en el head
        }

        if ($(valueOfElement).parent().hasClass('input-group')) {
            $(valueOfElement).parent().parent().last().append(html);
        } else {
            $(valueOfElement).parent().last().append(html);
        }

    });

    return flag
}