$( document ).ready(function() {
    $('input#phone').keydown(function(event) {
        if (event.keyCode === 13) {
            return true;
        }
        if (!(event.keyCode == 8 || event.keyCode == 46 || (event.keyCode >= 35 && event.keyCode <= 40) || (event.keyCode >= 48 && event.keyCode <= 57) || (event.keyCode >= 96 && event.keyCode <= 105))) {
            event.preventDefault();
        }
    });
    $('#username').keyup(function() {
        // alert('111');
        console.log($(this).val());
        var name = $(this).val().replace(/[^A-Z0-9]/gi, '');
         $('#username').val(name)
      });
    $('input#cmnd').keydown(function(event) {
        if (event.keyCode === 13) {
            return true;
        }
        if (!(event.keyCode == 8 || event.keyCode == 46 || (event.keyCode >= 35 && event.keyCode <= 40) || (event.keyCode >= 48 && event.keyCode <= 57) || (event.keyCode >= 96 && event.keyCode <= 105))) {
            event.preventDefault();
        }
    });


    $('#register-account').on('submit', function(event) {
        $.fn.existsWithValue = function() {

            return this.length && this.val().length;

        };
        var self = $(this);
        var isValidEmailAddress = function(email, callback) {
            var pattern = new RegExp(/^(("[\w-+\s]+")|([\w-+]+(?:\.[\w-+]+)*)|("[\w-+\s]+")([\w-+]+(?:\.[\w-+]+)*))(@((?:[\w-+]+\.)*\w[\w-+]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][\d]\.|1[\d]{2}\.|[\d]{1,2}\.))((25[0-5]|2[0-4][\d]|1[\d]{2}|[\d]{1,2})\.){2}(25[0-5]|2[0-4][\d]|1[\d]{2}|[\d]{1,2})\]?$)/i);
            callback(pattern.test(email));
        };
        // if (self.find('#province').existsWithValue() === 0) {
        //             self.find('#province').parent().addClass('has-error');
        //             self.find('#province-error').show();
        //             self.find('#province-error span').show();
        //             return false;
        //  }
        var validate = {
            init: function(self) {
                self.find('#username').parent().removeClass('has-error');
                self.find('#user-error').hide();
                self.find('#email').parent().removeClass('has-error');
                self.find('#email-error').hide();
                self.find('#phone').parent().removeClass('has-error');
                self.find('#phone-error').hide();
                self.find('#cmnd').parent().removeClass('has-error');
                self.find('#cmnd-error').hide();
                self.find('#country').parent().removeClass('has-error');
                self.find('#country-error').hide();
                self.find('#password').parent().removeClass('has-error');
                self.find('#password-error').hide();
                self.find('#password2').parent().removeClass('has-error');
                self.find('#password2-error').hide();
                self.find('#confirmpassword').parent().removeClass('has-error');
                self.find('#confirmpassword-error').hide();
                self.find('#confirmpasswordtransaction').parent().removeClass('has-error');
                self.find('#confirmpasswordtransaction-error').hide();
                $('#agreeTerm').is(":checked") && $('#agreeTerm').removeClass('validation-error');
            },

            userName: function(self) {
                if (self.find('#username').existsWithValue() === 0) {
                    self.find('#username').parent().addClass('has-error');
                    self.find('#user-error').show();
                    self.find('#user-error span').show();
                    return false;
                }
                return true;
            },

            email: function(self) {
                if (self.find('#email').existsWithValue() === 0) {
                    self.find('#email').parent().addClass('has-error');
                    self.find('#email-error').show();
                    self.find('#email-error span').show();
                    return false;
                }
                return true;
            },

            phone: function(self) {
                if (self.find('#phone').existsWithValue() === 0) {
                    self.find('#phone').parent().addClass('has-error');
                    self.find('#phone-error').show();
                    self.find('#phone-error span').show();
                    return false;
                }
                return true;
            },

            cmnd: function(self) {
                if (self.find('#cmnd').existsWithValue() === 0) {
                    self.find('#cmnd').parent().addClass('has-error');
                    self.find('#cmnd-error').show();
                    self.find('#cmnd-error span').show();
                    return false;
                }
                return true;
            },
            country: function(self) {
                if (self.find('#country').existsWithValue() === 0) {
                    self.find('#country').parent().addClass('has-error');
                    self.find('#country-error').show();
                    self.find('#country-error span').show();
                    return false;
                }
                return true;
            },
            password: function(self) {
                if (self.find('#password').existsWithValue() === 0) {
                    self.find('#password').parent().addClass('has-error');
                    self.find('#password-error').show();
                    self.find('#password-error span').show();
                    return false;
                }
                return true;
            },
            password_tran: function(self) {
                if (self.find('#password2').existsWithValue() === 0) {
                    self.find('#password2').parent().addClass('has-error');
                    self.find('#password2-error').show();
                    self.find('#password2-error span').show();
                    return false;
                }
                return true;
            },

            repeatPasswd: function(self) {
                if (self.find('#confirmpassword').val() !== self.find('#password').val()) {
                    self.find('#confirmpassword').parent().addClass('has-error');
                    self.find('#confirmpassword-error').show();
                    self.find('#confirmpassword-error span').show();
                    return false;
                }
                return true;
            },

            repeatPasswd_tran: function(self) {
                if (self.find('#confirmpasswordtransaction').val() !== self.find('#password2').val()) {
                    self.find('#confirmpasswordtransaction').parent().addClass('has-error');
                    self.find('#confirmpasswordtransaction-error').show();
                    self.find('#confirmpasswordtransaction-error span').show();
                    return false;
                }
                return true;
            },

            checkUserExit: function(self, callback) {
                if (self.find('#username').existsWithValue() !== 0) {
                    $.ajax({
                        url: self.find('#username').data('link'),
                        type: 'GET',
                        data: {
                            'username': self.find('#username').val()
                        },
                        async: false,
                        success: function(result) {
                            result = $.parseJSON(result);
                            callback(result.success === 0);
                        }
                    });
                }
            },

            checkEmailExit: function(self, callback) {
                if (self.find('#email').existsWithValue() !== 0) {
                    $.ajax({
                        url: self.find('#email').data('link'),
                        type: 'GET',
                        data: {
                            'email': self.find('#email').val()
                        },
                        async: false,
                        success: function(result) {

                            result = $.parseJSON(result);
            
                            callback(result.success === 0);
                        }
                    });
                }
            },
            checkPhoneExit: function(self, callback) {
                if (self.find('#phone').existsWithValue() !== 0) {
                    $.ajax({
                        url: self.find('#phone').data('link'),
                        type: 'GET',
                        data: {
                            'phone': self.find('#phone').val()
                        },
                        async: false,
                        success: function(result) {
                            result = $.parseJSON(result);
                            callback(result.success === 0);
                        }
                    });
                }
            },

            checkCMND: function(self, callback) {
                if (self.find('#cmnd').existsWithValue() !== 0) {
                    $.ajax({
                        url: self.find('#cmnd').data('link'),
                        type: 'GET',
                        data: {
                            'cmnd': self.find('#cmnd').val()
                        },
                        async: false,
                        success: function(result) {
                            result = $.parseJSON(result);
                            callback(result.success === 0);
                        }
                    });
                }
            },
        };


        validate.init($(this));
        if (validate.userName($(this)) === false) {
            return false;
        } else {
            validate.init($(this));
            self.find('#username').parent().addClass('has-success');
        }

        if (validate.email($(this)) === false) {
            return false;
        } else {
            var checkEmail = null;
            isValidEmailAddress(self.find('#email').val(), function(callback) {
                checkEmail = !callback ? true : false;
            });
            if (checkEmail) {
                self.find('#email').parent().addClass('has-error');
                self.find('#email-error').show();
                self.find('#email-error span').show();
                return false;
            } else {
                validate.init($(this));
                self.find('#email').parent().addClass('has-success');
            }
        }
        if (validate.phone($(this)) === false) {
            return false;
        } else {
            validate.init($(this));
            self.find('#phone').parent().addClass('has-success');
        }
        if (validate.cmnd($(this)) === false) {
            return false;
        } else {
            validate.init($(this));
            self.find('#cmnd').parent().addClass('has-success');
        }
        if (validate.country($(this)) === false) {
            return false;
        } else {
            validate.init($(this));
            self.find('#country').parent().addClass('has-success');
        }

        if (validate.password($(this)) === false) {
            return false;
        } else {
            validate.init($(this));
            self.find('#password').parent().addClass('has-success');
        }

        if (validate.password_tran($(this)) === false) {
            return false;
        } else {
            validate.init($(this));
            self.find('#password2').parent().addClass('has-success');
        }

        if (validate.repeatPasswd($(this)) === false) {
            return false;
        } else {
            validate.init($(this));
            self.find('#confirmpassword').parent().addClass('has-success');
        }

        if (validate.repeatPasswd_tran($(this)) === false) {
            return false;
        } else {
            validate.init($(this));
            self.find('#confirmpasswordtransaction').parent().addClass('has-success');
        }

        var checkUser = null;
        var checkEmail = null;
        var checkPhone = null;
        var checkCMND = null;

        validate.checkUserExit($(this), function(callback) {
            validate.init($(this));
            if (!callback) {
                self.find('#username').parent().addClass('has-error');
                self.find('#user-error').show();
                self.find('#user-error span').show();
                self.find('#password').val('');
                self.find('#password').parent().removeClass('has-success');
                self.find('#confirmpassword').val('');
                self.find('#confirmpassword').parent().removeClass('has-success');
                self.find('#password2').val('');
                self.find('#password2').parent().removeClass('has-success');
                self.find('#confirmpasswordtransaction').val('');
                self.find('#confirmpasswordtransaction').parent().removeClass('has-success');
                return false;
            } else {
                self.find('#username').parent().removeClass('has-error');
                self.find('#user-error').hide();
                self.find('#email').parent().removeClass('has-error');
                self.find('#email-error').hide();
                self.find('#phone').parent().removeClass('has-error');
                self.find('#phone-error').hide();
                self.find('#cmnd').parent().removeClass('has-error');
                self.find('#cmnd-error').hide();
                self.find('#country').parent().removeClass('has-error');
                self.find('#country-error').hide();
                self.find('#password').parent().removeClass('has-error');
                self.find('#password-error').hide();
                self.find('#password2').parent().removeClass('has-error');
                self.find('#password2-error').hide();
                self.find('#confirmpassword').parent().removeClass('has-error');
                self.find('#confirmpassword-error').hide();
                self.find('#confirmpasswordtransaction').parent().removeClass('has-error');
                self.find('#confirmpasswordtransaction-error').hide();
                $('#agreeTerm').is(":checked") && $('#agreeTerm').removeClass('validation-error');
                self.find('#username').parent().addClass('has-success');
                checkUser = true;
            }
        });

        if (checkUser) {
            validate.checkEmailExit($(this), function(callback) {
                if (!callback) {
                    self.find('#email').parent().addClass('has-error');
                    self.find('#email-error').show();
                    self.find('#email-error span').show();
                    self.find('#password').val('');
                    self.find('#password').parent().removeClass('has-success');
                    self.find('#confirmpassword').val('');
                    self.find('#confirmpassword').parent().removeClass('has-success');
                    self.find('#password2').val('');
                    self.find('#password2').parent().removeClass('has-success');
                    self.find('#confirmpasswordtransaction').val('');
                    self.find('#confirmpasswordtransaction').parent().removeClass('has-success');
                    return false;
                } else {
                    self.find('#username').parent().removeClass('has-error');
                    self.find('#user-error').hide();
                    self.find('#email').parent().removeClass('has-error');
                    self.find('#email-error').hide();
                    self.find('#phone').parent().removeClass('has-error');
                    self.find('#phone-error').hide();
                    self.find('#cmnd').parent().removeClass('has-error');
                    self.find('#cmnd-error').hide();
                    self.find('#country').parent().removeClass('has-error');
                    self.find('#country-error').hide();
                    self.find('#password').parent().removeClass('has-error');
                    self.find('#password-error').hide();
                    self.find('#password2').parent().removeClass('has-error');
                    self.find('#password2-error').hide();
                    self.find('#confirmpassword').parent().removeClass('has-error');
                    self.find('#confirmpassword-error').hide();
                    self.find('#confirmpasswordtransaction').parent().removeClass('has-error');
                    self.find('#confirmpasswordtransaction-error').hide();
                    $('#agreeTerm').is(":checked") && $('#agreeTerm').removeClass('validation-error');
                    self.find('#email').parent().addClass('has-success');
                    checkEmail = true;
                }
            });
        };

        if (checkUser && checkEmail) {
            validate.checkPhoneExit($(this), function(callback) {
                if (!callback) {
                    self.find('#phone').parent().addClass('has-error');
                    self.find('#phone-error').show();
                    self.find('#phone-error span').show();
                    self.find('#password').val('');
                    self.find('#password').parent().removeClass('has-success');
                    self.find('#confirmpassword').val('');
                    self.find('#confirmpassword').parent().removeClass('has-success');
                    self.find('#password2').val('');
                    self.find('#password2').parent().removeClass('has-success');
                    self.find('#confirmpasswordtransaction').val('');
                    self.find('#confirmpasswordtransaction').parent().removeClass('has-success');
                    return false;
                } else {
                    self.find('#username').parent().removeClass('has-error');
                    self.find('#user-error').hide();
                    self.find('#email').parent().removeClass('has-error');
                    self.find('#email-error').hide();
                    self.find('#phone').parent().removeClass('has-error');
                    self.find('#phone-error').hide();
                    self.find('#cmnd').parent().removeClass('has-error');
                    self.find('#cmnd-error').hide();
                    self.find('#country').parent().removeClass('has-error');
                    self.find('#country-error').hide();
                    self.find('#password').parent().removeClass('has-error');
                    self.find('#password-error').hide();
                    self.find('#password2').parent().removeClass('has-error');
                    self.find('#password2-error').hide();
                    self.find('#confirmpassword').parent().removeClass('has-error');
                    self.find('#confirmpassword-error').hide();
                    self.find('#confirmpasswordtransaction').parent().removeClass('has-error');
                    self.find('#confirmpasswordtransaction-error').hide();
                    $('#agreeTerm').is(":checked") && $('#agreeTerm').removeClass('validation-error');
                    self.find('#phone').parent().addClass('has-success');
                    checkPhone = true;
                }
            });
        };
        if (checkUser && checkEmail && checkPhone) {
            validate.checkCMND($(this), function(callback) {
                if (!callback) {
                    self.find('#cmnd').parent().addClass('has-error');
                    self.find('#cmnd-error').show();
                    self.find('#cmnd-error span').show();
                    self.find('#password').val('');
                    self.find('#password').parent().removeClass('has-success');
                    self.find('#confirmpassword').val('');
                    self.find('#confirmpassword').parent().removeClass('has-success');
                    self.find('#password2').val('');
                    self.find('#password2').parent().removeClass('has-success');
                    self.find('#confirmpasswordtransaction').val('');
                    self.find('#confirmpasswordtransaction').parent().removeClass('has-success');
                    return false;
                } else {
                    self.find('#username').parent().removeClass('has-error');
                    self.find('#user-error').hide();
                    self.find('#email').parent().removeClass('has-error');
                    self.find('#email-error').hide();
                    self.find('#phone').parent().removeClass('has-error');
                    self.find('#phone-error').hide();
                    self.find('#cmnd').parent().removeClass('has-error');
                    self.find('#cmnd-error').hide();
                    self.find('#country').parent().removeClass('has-error');
                    self.find('#country-error').hide();
                    self.find('#password').parent().removeClass('has-error');
                    self.find('#password-error').hide();
                    self.find('#password2').parent().removeClass('has-error');
                    self.find('#password2-error').hide();
                    self.find('#confirmpassword').parent().removeClass('has-error');
                    self.find('#confirmpassword-error').hide();
                    self.find('#confirmpasswordtransaction').parent().removeClass('has-error');
                    self.find('#confirmpasswordtransaction-error').hide();
                    $('#agreeTerm').is(":checked") && $('#agreeTerm').removeClass('validation-error');
                    self.find('#cmnd').parent().addClass('has-success');
                    checkCMND = true;
                }
            });
        }
        if (!self.find('#agreeTerm').is(":checked")) {
            self.find('#agreeTerm').addClass('validation-error');
            return false;
        } else {
            $('#agreeTerm').is(":checked") && $('#agreeTerm').removeClass('validation-error');
        }

        
        if(checkUser && checkEmail && checkPhone && checkCMND && self.find('#agreeTerm').is(":checked")){  
            $('.alert-edit-account').show();

        window.funLazyLoad.start();
         // alert('Register account success!');
             return true;         
        }

        return false;

    });
});