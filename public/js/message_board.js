$(function() {
    $('#message_submit').on('click', function (e) {
       e.preventDefault();
       ajaxSubmitMessage($(this));
    });

    var hasFormErrors = false;
    function ajaxSubmitMessage(submit) {
        var form = submit.closest('form'),
            hasFormErrors = false,
            firstNameField = $('#first_name'),
            lastNameField = $('#last_name'),
            birthdateField = $('#birthdate'),
            emailField = $('#email'),
            messageField = $('#message');

        removeErrorClassFromFields();
        validateFormFields(form);
        validateEmail(emailField);
        if (hasFormErrors === false) {
            toggleLoader(submit);
            disableFormFiels(form);

            $.ajax({
                url:'/new-message',
                method: 'POST',
                dataType: "json",
                data: {
                    'first_name':firstNameField.val(),
                    'last_name':lastNameField.val(),
                    'birthdate':birthdateField.val(),
                    'email': emailField.val(),
                    'message': messageField.val()
                },
                success: function (data) {

                    setTimeout(function(){
                        enableFormFiels(form);
                        toggleLoader(submit);

                        if (data.status === 'error') {
                            addErrorFromAjaxResponse(data.data);
                        } else {
                            addNewMessage(data.data);
                        }
                    }, 2000);


                }
            });
        }
    }

    /*
    builds and adds a new entry to the message board
     */
    function addNewMessage(data)
    {
       var messagesContainer = $('#message-container'),
           message = '<li>' +
               '<div>' +
               '<span>'+ data.message.time +'</span>';

       if (data.user.email !== '') {
           message += '<a href="mailto:'+ data.user.email +'">'+ data.user.first_name +' '+ data.user.last_name +'</a>';
       } else {
           message += data.user.first_name +' '+ data.user.last_name;
       }
           message += ', ' + data.user.age + 'm. </div>' +
               '<div>' +
                    data.message.message +
               '</div>' +
           '</li>';
       
        messagesContainer.prepend(message);
        removeLastVisibleMessage(messagesContainer);
    }

    /*
    removes last visible message
     */
    function removeLastVisibleMessage(messagesContainer) {
      if (messagesContainer.children().length > 5) {
          messagesContainer.find('li:last-child').remove();
      }
    }
    /*
    adds error class to incorrectly filled fields
     */
    function addErrorFromAjaxResponse(data)
    {
        removeErrorClassFromFields();
        $.each(data, function (key, value) {
           var element =  $('[name="' + key + '"]').parent('p');
           if (!element.hasClass(value)) {
               element.addClass(value);
           }
        });
    }

    /*
    removes error class from all input fields
     */
    function removeErrorClassFromFields()
    {
       var elements = $('input, textarea')  ;
       $.each(elements, function (key, element) {
          $(element).parent('p').removeClass('err');
       });
    }
    /*
    toggles between submit button and loader gif
     */
    function toggleLoader(submit) {
        $('#loader-gif').toggleClass('hidden');
        submit.toggleClass('hidden');
    }

    /*
    disables fields for input
     */
    function disableFormFiels(form) {
        form.find('input, textarea').each(function () {
            $(this).prop('disabled', true);
        });
    }

    /*
    enables fields for input
     */
    function enableFormFiels(form) {
        form.find('input, textarea').each(function () {
            $(this).prop('disabled', false);
        });
    }

    /*
    gets all input and text area inputs from form and checks if required fields have values
     */
    function validateFormFields(form)
    {
        form.find('input, textarea').each(function () {
            if($(this).prop('required') === true && $(this).prop('name') !== 'undefined') {
                if ($(this).val() === '') {
                    console.log($(this).prop('required'));
                    addErrorClassToElement($(this).closest('p'));
                }
            }
        });
    }

    /*
    adds an error class to the given element
     */
    function addErrorClassToElement(element)
    {
        if(!element.hasClass('err')) {
            element.addClass('err');
            hasFormErrors = true;
        }
    }
    /*
    protects first and last name fields from invalid and unsupported characters input
     */
    $('#first-name, #last-name').keydown(function (e) {
        if (e.shiftKey || e.ctrlKey || e.altKey) {
            e.preventDefault();
        } else {
            var key = e.keyCode;
            if (!((key == 8) || (key == 32) || (key == 46) || (key >= 35 && key <= 40) || (key >= 65 && key <= 90))) {
                e.preventDefault();
            }
        }
    });

    /*
    protects first and last name fields from 'mouse pasting' invalid and unsupported characters
     */
    $("#first-name, #last-name").on("input", function(){
        var regexp = /[^a-zA-Z]/g;
        if($(this).val().match(regexp)){
            $(this).val( $(this).val().replace(regexp,'') );
        }
    });

    /*
    checks if entered email address is valid
     */
    function validateEmail(emailField) {
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        if (emailField.val() !== '' && !emailReg.test(emailField.val())){
            addErrorClassToElement(emailField.parent('p'));
        }
    }
});