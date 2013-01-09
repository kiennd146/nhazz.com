/**
 * @version     2.0.1
 * @package     com_vitabook
 * @copyright   Copyright (C) 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      JoomVita - http://www.joomvita.com
 */

var vitabook = {
    currentId: 0,
    currentType: 'none',
    reset: function (){
        // method to reset the form
        // cancel submit-button lock
        $('vbMessageFormSubmitButton').disabled = false;
        $('vbAjaxBusy').hide();
        // if current type: edit, restore form to original message contents
        if(vitabook.currentType == 'edit')
        {
            tinymce.get('jform_message').setContent($('vbMessageMessage_'+vitabook.currentId).getFirst('div.vbMessageText').get('html'));
            var message = $('vbMessage_'+vitabook.currentId);
            $('jform_name').set('value', message.get('data-name'));
            $('jform_email').set('value', message.get('data-email'));
            if($('jform_site'))
                $('jform_site').set('value', message.get('data-site'));
            if($('jform_location'))
                $('jform_location').set('value', message.get('data-location'));
        }
        // else reset to onload defaults
        else
        {
            // reset de normal form fields
            $('vitabookMessageForm').reset();
            tinymce.get('jform_message').setContent('');
            // current type: reply, retain parent_id while resetting
            if(vitabook.currentType == 'reply')
            {
                $('jform_parent_id').value = vitabook.currentId;
            }
        }
    },
    fresh: function (){
        // do nothing if form is already in place
        if(vitabook.currentType == 'fresh')
        {
            return;
        }
        // if previous location is reply, retain form contents, only change parent_id and move form
        if(vitabook.currentType == 'reply')
        {
            $('jform_parent_id').value = 1;
            tinymce.execCommand('mceRemoveControl',true,'jform_message');
            $('vbMessageHeader_'+vitabook.currentId).removeClass('vbActiveParent');
            $('vbMessageMessage_'+vitabook.currentId).removeClass('vbActiveParent');
            $('vbFormHolder').grab($('vbMessageForm'));
            vitabook.currentId = 0;
        }
        // cancel form if it's currently active elsewhere
        else if(vitabook.currentType != 'none')
        {
            vitabook.cancel(vitabook.fresh);
            return;
        }
        // reconnect tinymce
        tinymce.execCommand('mceAddControl',true,'jform_message');
        // show form
        $('vbMessageForm').reveal({ duration: 250 });
        // set status variables
        vitabook.currentType = 'fresh';
        // scroll to form
        var position = $('vbMessageForm').getPosition();
        window.scrollTo(position.x,position.y-100);
        // grab focus
        vitabook.grabFocus();
    },
    reply: function (parentId){
        if(vitabook.currentType == 'fresh')
        {
            // only have to disable editor before moving :-)
                tinymce.execCommand('mceRemoveControl',true,'jform_message');
        }
        else if (vitabook.currentType == 'reply')
        {
            tinymce.execCommand('mceRemoveControl',true,'jform_message');
            $('vbMessageHeader_'+vitabook.currentId).removeClass('vbActiveParent');
            $('vbMessageMessage_'+vitabook.currentId).removeClass('vbActiveParent');
        }
        // cancel form if it's currently active
        else if(vitabook.currentType != 'none')
        {
            vitabook.cancel(vitabook.reply.bind(this, parentId));
            return;
        }
        // move form
        $('jform_parent_id').value = parentId;
        $('vbMessageChildren_'+parentId).adopt($('vbMessageForm'));
        // reconnect tinymce
        tinymce.execCommand('mceAddControl',true,'jform_message');
        // set active parent
        $('vbMessageHeader_'+parentId).addClass('vbActiveParent');
        $('vbMessageMessage_'+parentId).addClass('vbActiveParent');
        // show form
        $('vbMessageForm').reveal({ duration: 250 });
        // scroll to form
        var position = $('vbMessageForm').getPosition();
        window.scrollTo(position.x,position.y);
        // grab focus
        vitabook.grabFocus();
        // set status variables
        vitabook.currentId = parentId;
        vitabook.currentType = 'reply';
    },
    edit: function (messageId){
        // cancel form if it's currently active
        if(vitabook.currentType != 'none')
        {
            vitabook.cancel(vitabook.edit.bind(this, messageId));
            return;
        }
        // put content of the message in the formfield
        var messageContent = $('vbMessageMessage_'+messageId).getFirst('div.vbMessageText').get('html');
        $('jform_message').set('value', messageContent);
        var message = $('vbMessage_'+messageId);
        $('jform_name').set('value', message.get('data-name'));
        $('jform_email').set('value', message.get('data-email'));
        if($('jform_site'))
            $('jform_site').set('value', message.get('data-site'));
        if($('jform_location'))
            $('jform_location').set('value', message.get('data-location'));
        $('jform_parent_id').set('value', message.get('data-parent_id'));
        // move the editor to new location
        $('vbMessage_'+messageId).getParent().grab($('vbMessageForm'),'top');
        // reconnect tinymce (with small delay so IE7&8 work also)
        (function(){tinymce.execCommand('mceAddControl',true,'jform_message');}).delay(50);
        // set correct message id in form
        $('jform_id').set('value', messageId);
        // add the message-id to the action of the form
        // hide original message
        $('vbMessage_'+messageId).hide();
        // show form
        $('vbMessageForm').reveal({ duration: 250 });
        // set status variables
        vitabook.currentId = messageId;
        vitabook.currentType = 'edit';
    },
    cancel: function (followUp){
        var delay = 0;
        // type specific cancel procedures
        switch (vitabook.currentType)
        {
            case 'edit':
                // hide form
                $('vbMessageForm').hide();
                // unhide original message
                $('vbMessage_'+vitabook.currentId).set('opacity',0.5);
                $('vbMessage_'+vitabook.currentId).show();
                if (Browser.ie && Browser.version < 9){
                    $('vbMessage_'+vitabook.currentId).set('opacity',1);
                }
                else{
                    $('vbMessage_'+vitabook.currentId).tween('opacity', 1);
                }
                $('vitabookMessageForm').reset();
                break;
            case 'reply':
                // hide form
                $('vbMessageForm').hide();
                // reset active parent
                $('vbMessageHeader_'+vitabook.currentId).removeClass('vbActiveParent');
                $('vbMessageMessage_'+vitabook.currentId).removeClass('vbActiveParent');
                // reset parent_id
                $('jform_parent_id').value = 1;
                break;
            case 'fresh':
                // hide form
                $('vbMessageForm').dissolve({ duration: 250 });
                delay = 200;
                break;
            default:
        }
        // delay execution of below to allow above to finish first
        (function(){
            // general
            tinymce.execCommand('mceRemoveControl',true,'jform_message');
            // reset values
            $('jform_id').value = 0;
            $('jform_message').set('value', '');
            $('vbMessageFormSubmitButton').disabled = false;
            $('vbAjaxBusy').hide();
            // move form to parking
            $('vbFormHolder').grab($('vbMessageForm'));
            // set status variables
            vitabook.currentId = 0;
            vitabook.currentType = 'none';
            if(followUp)
            {
                followUp();
            }
            // reload captcha if present
            if(typeof( Recaptcha ) != 'undefined')
            {
                Recaptcha.reload();
            }
        }).delay(delay);
    },
    loadChildren: function(element,parent_id,start){
        //make the ajax call, insert child messages
        new Request.HTML({
            url: vitabook.url_base,
            onRequest: function(){
                // especially for IE7 not willing to coorporate, no references to element
                $('vbLoadMoreMessages_'+parent_id+'_'+start).set('opacity', 0.5); // basic indication that we're busy
                $('vbLoadMoreMessages_'+parent_id+'_'+start).set('onclick', '');  // prevent multiple clicks
            },
            onComplete: function(response) {
                // remove this load-more-messages button/div
                $('vbLoadMoreMessages_'+parent_id+'_'+start).destroy();
//TODO: get next siblings position, so we can scroll to that location :-)
                // put newly loaded messages in place
                var oud = $('vbMessageChildren_'+parent_id).getChildren('div');
                $('vbMessageChildren_'+parent_id).adopt(response);
                $('vbMessageChildren_'+parent_id).adopt(oud);
                vitabook.visualState(parent_id,$('vbMessageHeader_'+parent_id).hasClass('vbMessageUnpublished')? 0 : 1);
            }
        }).get('task=vitabook.getMessages&start='+start+'&format=raw&'+vitabook.token+'=1&parent_id='+parent_id);
    },
    state: function(element, id){
        var task = 'unpublish';
        if($('vbMessageHeader_'+id).get('data-published')==0)
            task = 'publish';
        //make the ajax call
        new Request.JSON({
            url: vitabook.url_base,
            onSuccess: function(response){
                if(response.success===1)
                {
                    // switch-controls and data attribute
                    $('vbMessageHeader_'+id).set('data-published', response.state);
                    $('vbMessageMessage_'+id).set('data-published', response.state);
                    $('vbMessageStateControl_'+id).src = response.state ? $('vbMessageStateControl_'+id).src.replace('offline','online') : $('vbMessageStateControl_'+id).src.replace('online','offline');
                    // actions for messages now unpublished
                    vitabook.visualState(id,response.state);
                }
                else
                {
                    // show errors
                    alert(response.state);
                }
            }
        }).get('task=message.'+task+'&messageId='+id+'&'+vitabook.token+'=1&format=raw');
    },
    remove: function(element, id){
        //make the ajax call
        new Request.JSON({
            url: vitabook.url_base,
            onSuccess: function(response) {
                if(response.success===1)
                {
                    // when success, remove this message and all possible children from DOM
                    $('vbMessage_'+id).getParent().dissolve({ duration: 250 });
                    (function(){$('vbMessage_'+id).getParent().destroy();}).delay(250);
                }
                else
                {
                    // show errors
                    alert(response.state);
                }
            }
        }).get('task=message.delete&messageId='+id+'&'+vitabook.token+'=1&format=raw');
    },
    save: function(element){
        // write tinymce content to textarea
        tinyMCE.triggerSave();
        if(vitabook.validate())
        {
            //make the ajax call
            new Request.JSON({
                url: vitabook.url_base,
                method: 'post',
                data: $('vitabookMessageForm'),
                onSuccess: function(response) {
                    switch(response.state){
                        // routines for successfull saves (published right away)
                        case 1:
                            if(vitabook.currentType == 'edit')
                            {
                                // edit was successfull, update message div with new content
                                new Request.HTML({
                                    url: vitabook.url_base,
                                    onComplete: function(message) {
                                        var place = $('vbMessage_'+response.result).getParent();
                                        var children = $('vbMessageChildren_'+response.result).getChildren('div');
                                        $('vbMessage_'+response.result).destroy();
                                        $('vbMessageChildren_'+response.result).destroy();
                                        place.adopt(message);
                                        $('vbMessageChildren_'+response.result).adopt(children);
                                    }
                                }).get('task=message.getMessage&format=raw&messageId='+response.result+'&'+vitabook.token+'=1');
                                // and reset form
                                vitabook.cancel();
                            }
                            else
                            {
                                if(vitabook.currentType == 'fresh')
                                {
                                // code for new messages
                                    new Request.HTML({
                                        url: vitabook.url_base,
                                        onComplete: function(message){
                                            var oud = $('vbMessages').getChildren('div');
                                            $('vbMessages').adopt(message);
                                            $('vbMessages').adopt(oud);
                                            vitabook.cancel();
                                            if($('vbNoMessages'))
                                            {
                                                $('vbNoMessages').destroy();
                                            }
                                        }
                                    }).get('task=message.getMessage&format=raw&messageId='+response.result+'&'+vitabook.token+'=1');
                                }
                                if(vitabook.currentType == 'reply')
                                {
                                // code for new replies
                                    new Request.HTML({
                                        url: vitabook.url_base,
                                        onComplete: function(message) {
                                            $('vbMessageChildren_'+vitabook.currentId).adopt(message);
                                            vitabook.cancel();
                                            vitabook.visualState($('vbMessageHeader_'+response.result).get('data-parent_id'),$('vbMessageHeader_'+$('vbMessageHeader_'+response.result).get('data-parent_id')).hasClass('vbMessageUnpublished')? 0 : 1);
                                        }
                                    }).get('task=message.getMessage&format=raw&messageId='+response.result+'&'+vitabook.token+'=1');
                                }
                            }
                            break;
                         // routine for saves awaiting moderation
                        case 2:
                            // remove form
                            vitabook.cancel();
                            // show errors/message
                            (function(){alert(response.result);}).delay(260);
                        break;
                        // routine for failed saves
                        case 0:
                            alert(response.result);
                            // reload captcha if present
                            if(typeof( Recaptcha ) != 'undefined'){
                                Recaptcha.reload();
                            }
                            $('vbMessageFormSubmitButton').disabled = false;
                            $('vbAjaxBusy').hide();
                        break;
                        default:
                    }
                }
            }).send();
        }
        else
        {
            $('vbMessageFormSubmitButton').disabled = false;
            $('vbAjaxBusy').hide();
        }
    return false;
    },
    grabFocus: function(){
        // set focus on name if empty
        if($('jform_name').value == '')
            $('jform_name').focus();
        // else focus on editor, with slight delay giving tinymce time to initialize
        else
            (function(){tinyMCE.execCommand('mceFocus',false,'jform_message');}).delay(100);
    },
    // changes visual state of message with id and all it's children
    visualState: function(id, state){
        // actions for messages now unpublished
        if(state==0)
        {
            // change color (including all children)
            $('vbMessage_'+id).getParent().getElements('div.vbMessageHeader').each(function(el){el.addClass('vbMessageUnpublished');});
            $('vbMessage_'+id).getParent().getElements('div.vbMessageMessage').each(function(el){el.addClass('vbMessageUnpublished');});
            // change control of this message
        }
        // actions for messages now published
        else
        {
            // change color (including all children)
//TODO: this probably can be done more efficiently
            $('vbMessage_'+id).getParent().getElements('div[class^=vbMessageHeader]').each(function(el){
                if(el.get('data-published')==1 && ($('vbMessageHeader_'+el.get('data-parent_id')) === null || !($('vbMessageHeader_'+el.get('data-parent_id')).hasClass('vbMessageUnpublished'))))
                    el.removeClass('vbMessageUnpublished');
            });
            $('vbMessage_'+id).getParent().getElements('div[class^=vbMessageMessage]').each(function(el){
                if(el.get('data-published')==1 && ($('vbMessageMessage_'+el.get('data-parent_id')) === null || !($('vbMessageMessage_'+el.get('data-parent_id')).hasClass('vbMessageUnpublished'))))
                    el.removeClass('vbMessageUnpublished');
            });
        }
    }
};
