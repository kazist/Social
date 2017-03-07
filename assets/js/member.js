/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


jQuery(document).ready(function () {
    member.init();
});

member = function () {
    return {
        phone_country: '',
        init: function () {

            member.addEvents(jQuery('body'));
            member.getLocationOptions(jQuery('.country_id-group select'));

        }, addEvents: function (html) {

            member.changePhoneFlag(html.find('#country_id'));

            var default_inviter =parseInt(html.find('#default_inviter').val());
            
            if(default_inviter){
                   jQuery('.registration-button').show();
            }

            html.find('#captcha').val('');
            html.find('form').on('submit', function () {

                var input_phone = jQuery("#phone");
                var selected_country = input_phone.intlTelInput("getSelectedCountryData");

                jQuery("#phone_iso").val(selected_country.iso2);
                jQuery("#phone_code").val(selected_country.dialCode);

                return member.validateForm(jQuery(this));
            });

            html.find('#password').on('input', function () {
                member.validatePasswordForm(jQuery('#password_again'));
            });

            html.find('#password_again').on('input', function () {
                member.validatePasswordForm(jQuery(this));
            });

            html.find('#email').on('input', function () {
                member.validateEmailForm(jQuery('#email_again'));
            });

            html.find('#email_again').on('input', function () {
                member.validateEmailForm(jQuery(this));
            });

            html.find('#country_id').on('change', function () {
                member.getLocationOptions(jQuery(this));
                member.changePhoneFlag(jQuery(this));
            });

            html.find('.search-inviter').on('click', function () {
                member.getMemberInviter(jQuery(this));
            });

            html.find('.refresh-captcha').on('click', function () {
                member.changeCaptcha(jQuery(this));
            });

            html.find('.has_inviter input').on('ifChecked', function () {

                var inviter_val = parseInt(html.find(this).val());

                if (inviter_val) {
                    member.clearMemberInviter();
                    html.find('.inviter-group').show();
                } else {
                    member.getMemberInviter(jQuery(this));
                    html.find('.inviter-group').hide();
                }
            });

            member.validateEmailForm(jQuery('#email_again'));

            keyword = jQuery('.inviter-group input#inviter').val();
            is_default = parseInt(jQuery('input#is_default').val());

            if (keyword !== '' && typeof keyword !== typeof undefined && !is_default) {
                member.getMemberInviter(jQuery('.inviter-group'), keyword);
            }

            return html;

        }, validatePasswordForm: function (this_element) {

            if (this_element.val() !== jQuery('#password').val()) {
                jQuery('#password_again').each(function () {
                    this.setCustomValidity('Password Must be Matching.');
                });
            } else {
                jQuery('#password_again').each(function () {
                    this.setCustomValidity('');
                });
            }
        }, validateEmailForm: function (this_element) {

            if (this_element.val() !== jQuery('#email').val()) {
                jQuery('#email_again').each(function () {
                    console.log('Email Must be Matching.');
                    this.setCustomValidity('Email Must be Matching.');
                });
            } else {
                jQuery('#email_again').each(function () {
                    this.setCustomValidity('');
                });
            }
        }, validateForm: function (this_element) {

            var has_error = false;
            var msg = title = 'Registration Form Has Errors';

            var inviter_id = this_element.find('.inviter_id').val();
            var name = this_element.find('.name').val();
            var username = this_element.find('.username').val();
            var email = this_element.find('.email').val();
            var password = this_element.find('.password').val();
            var password_again = this_element.find('.password_again').val();
            var id_passport = this_element.find('.id_passport').val();

            if (inviter_id === '') {
                has_error = true;
                msg += '<br>-<b>Inviter</b> Not Defined.<br>';
            }

            if (name === '') {
                has_error = true;
                msg += '-<b>Name</b> is a required Field. It can not be Empty.<br>';
            }
            if (username === '') {
                has_error = true;
                msg += '-<b>Username</b> is a required Field. It can not be Empty.<br>';
            }
            if (email === '') {
                has_error = true;
                msg += '-<b>Email</b> is a required Field. It can not be Empty.<br>';
            }

            if (password === '' && this_element.find('.password').length) {
                has_error = true;
                msg += '-<b>Password</b> is a required Field. It can not be Empty.<br>';
            }
            if (password_again === '' && this_element.find('.password_again').length) {
                has_error = true;
                msg += '-<b>Pasword Again</b> is a required Field. It can not be Empty.<br>';
            }
            if (id_passport === '') {
                has_error = true;
                msg += '-<b>Id or Passport</b> is a required Field. It can not be Empty.<br>';
            }

            if (has_error) {
                kazist.showDialog(title, msg, 'error');
                return false;
            } else {
                return true;
            }
        }, clearMemberInviter: function () {

            jQuery('.inviter_detail-group .inviter_detail_name').html('');
            jQuery('.inviter-group .inviter_id').val('');
            jQuery('.inviter_detail-group').hide();

        }, changeCaptcha: function () {

            //   var data_object = {};
            var src = jQuery('.captcha-group .captcha-image').attr('src');
            var url_src = src + '?' + (new Date()).getTime();

            jQuery('.captcha-group .captcha-image-wrapper img').remove();
            jQuery('.captcha-image-wrapper').html('<img class="captcha-image" src="' + url_src + '">');

            //jQuery('.captcha-group #captcha').attr('src', src + '&' + (new Date()).getTime());

        }, getMemberInviter: function (this_element, keyword) {

            if (typeof keyword === 'undefined') {
                keyword = this_element.closest('.inviter-group').find('input').val();
            }

            var data_object = {keyword: keyword};

            var user = kazist.callAjax(kazist_document.web_base + '/social/members/ajaxinviter', data_object, true);

            if (typeof user.id !== 'undefined' && user !== null) {

                inviter_id = user.id;

                inviter_html = '<b>Name: </b>' + user.name + '(' + user.username + ')' + '<br>'
                        + '<b>Email: </b> ' + user.email + '<br>'
                        + '<b>Phone: </b> ' + user.phone + '<br>'
                        ;

                jQuery('.registration-button').show();

            } else {
                inviter_id = '';
                inviter_html = '<font color="red">Inviter Not Found.</font>'
            }

            jQuery('.inviter_detail-group .inviter_detail_name').html(inviter_html);
            jQuery('.inviter-group .inviter_id').val(inviter_id);
            jQuery('.inviter_detail-group').show();

        }, phoneInputEvent: function (code) {


            jQuery("#phone").intlTelInput("destroy");

            jQuery("#phone").intlTelInput({preferredCountries: ['ke', 'us', 'gb'], initialCountry: code, autoPlaceholder: true, autoHideDialCode: false});

        }, changePhoneFlag: function (this_element) {

            var country_id = parseInt(this_element.val());

            if (country_id) {
                var country_code = countries_codes[country_id].toLowerCase();
                member.phoneInputEvent(country_code);
            } else {
                member.phoneInputEvent('ke');
            }


        }, getLocationOptions: function (this_element) {

            var country_id = this_element.val();

            if (country_id) {
                var country_code = countries_codes[country_id].toLowerCase();
            } else {
                country_code = 'ke';
            }

            var data_object = {country_code: country_code, country_id: country_id, location_id: location_id};

            this_element.closest('form').find('.location_id-group').hide();

            var form = kazist.callAjax(kazist_document.web_base + '/setup/regions/ajaxloadregionoption', data_object, true);

            if (form !== '') {
                this_element.closest('form').find('.location_id-group').show();
                this_element.closest('form').find('#location_id').html(form);
            }

            return false;

        }

    };
}();