const checkPassword = () => {
    const input = document.getElementById('password');
    const value = input.value;
    validatePassword(value);
    setTimeout(function() { checkPassword() }, 1000);
}

const validations = {
    length: {
        id:'8chars',
        regex:/^.*(?=.{8,50}).*$/
    },
    upperCase: {
        id:'upper',
        regex:/^.*(?=.*[A-Z]).*$/
    },
    number: {
        id:'number',
        regex:/^.*(?=.*\d).*$/
    },
    specialChar: {
        id:'specialchar',
        regex:/^.*(?=.*[!@#$^&*]).*$/
    }
}

const validatePassword = (password) => {
    validate(password, validations.length);
    validate(password, validations.upperCase);
    validate(password, validations.number);
    validate(password, validations.specialChar);
}

const validate = (password, validation) => {
    if(validation.regex.test(password)){
        const span = document.getElementById(validation.id);
        span.firstChild.innerHTML='&#x2714';
        span.firstChild.classList.remove('red');
        span.firstChild.classList.add('green');
    }
}