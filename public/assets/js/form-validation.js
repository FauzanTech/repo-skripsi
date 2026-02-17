function validation(forms) {
    let valid = true;
    forms.forEach(element => {
        console.log({ element });
        if (element.rule == 'required' && (element.value == '' || element.value == null || element.value == undefined)) {
            $('#'+element.id).text(element.error_message);
            $('#'+element.form_id).addClass('is-invalid');
            valid = false;
        }
    });
    return valid;
}

function clearValidation(forms) {
    forms.forEach(element => {
        $('#'+element.id).text('');
        $('#'+element.form_id).removeClass('is-invalid');
    });
}

function setErrors(rules, errors) {
    const keys = Object.keys(errors);
    keys.forEach(key => {
        elementId = rules[key];
        $('#'+elementId).text(errors[key]);
        $('#'+key).addClass('is-invalid');
    })
}

function clearErrors(rules) {
    const keys = Object.keys(rules);
    keys.forEach(key => {
        elementId = rules[key];
        $('#'+elementId).text('');
        $('#'+key).removeClass('is-invalid');
    });
}
