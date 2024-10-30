(function() {
    tinymce.create('tinymce.plugins.cpDonations', {
        init : function(ed, url) {
			
			var t = this;
            
			ed.addButton('cpdselector', {
                title : 'CP Donations',
				text : 'CP Donations',
                cmd : 'cpdselector'
                //image :  url + '/code.png'				
            });
			
			ed.addCommand('cpdselector', function() {
                var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
                    W = W - 80;
                    H = H - 84;
                    tb_show( 'Insert CP Donations shortcode', '#TB_inline?width=' + W + '&height=' + H + '&inlineId=cp-donations-form' );
                				
            });
        }
        // ... Hidden code
    });
    // Register plugin
    tinymce.PluginManager.add( 'cpdbutton', tinymce.plugins.cpDonations );
})();

jQuery(function(){
    // creates a form to be displayed everytime the button is clicked
    // you should achieve this using AJAX instead of direct html code like this
    var form = jQuery('<div id="cp-donations-form"><table id="cp-donations-table" class="form-table" style="text-align: left">\
         \
            \
        <tr>\
        <th><label class="title" for="cp-donations-select">CP Donations</label></th>\
            <td><select id="cp-donations-select">\
</select><br />\
        </td>\
        </tr>\</table>\
    <p class="submit">\
        <input type="button" id="cp-donations-insert" class="button-primary" value="Insert shortcode" name="submit" style=" margin: 10px 150px 50px; float:left;"/>\
    </p>\
    </div>');

    var table = form.find('table');
    form.appendTo('body').hide();
	
	var donations;
	var donationOptions;
	
	jQuery.ajax({
		type: "POST",
		url: '../wp-content/plugins/custom-post-donations/admin/ws.php',
		success: function(result) {
			donations = result.cpDonations;
			for (var i = 0; i < donations.length; i++) {
				donationOptions += "<option value='"+donations[i].id+"'>"+donations[i].name+"</option>";
			}
			jQuery('#cp-donations-select').append(donationOptions);
		}
	});

    // handles the click event of the submit button
    jQuery('#cp-donations-insert').on('click', function(){
        // defines the options and their default values
        // again, this is not the most elegant way to do this
        // but well, this gets the job done nonetheless        

		var key = jQuery('#cp-donations-select option:selected').val();
        var shortcode = "[cpDonation key='"+key+"']";
         

        // inserts the shortcode into the active editor
        tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);

        // closes Thickhighlight
        tb_remove();
    });
});