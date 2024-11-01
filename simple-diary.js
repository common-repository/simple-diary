// Change new reminder link in admin toolbar after publishing the post
wp.data.subscribe( function() {
    const { __, _x, _n, _nx } = wp.i18n; // for translation
    if(wp.data.select( 'core/editor' ).isCurrentPostPublished()) {
        var pId = wp.data.select("core/editor").getCurrentPostId();
        var pTitle = wp.data.select('core/editor').getEditedPostAttribute( 'title' );
        if(pTitle != '') {
            var UpdatedlinkUrl = 'post-new.php?post_type=reminder&amp;simdiaw_p_id=' + pId + '&amp;simdiaw_p_title=' + encodeURI(pTitle);
            jQuery(".simdiaw_publish_for_new_reminder a").attr("href", UpdatedlinkUrl);
            jQuery(".simdiaw_publish_for_new_reminder a").html('<span class="ab-icon dashicons dashicons-pressthis"></span>' + __('Create a reminder for this post', 'simple-diary'));
            jQuery("#wp-admin-bar-new_reminder").attr("class", "simdiaw_new_reminder");
        }
    } else {
        jQuery(".simdiaw_new_reminder a").attr("href", "#");
        jQuery(".simdiaw_new_reminder a").html('<span class="ab-icon dashicons dashicons-pressthis"></span>' + __('Publish the post to create a new reminder', 'simple-diary'));
        jQuery("#wp-admin-bar-new_reminder").attr("class", "simdiaw_publish_for_new_reminder");
    }
});

// Retrieving query string values and adding them to new reminder form
jQuery(document).ready(function(){
    // Function to get the query string values on reminder creation form [ https://davidwalsh.name/query-string-javascript ]
    function getUrlParameter(name) {
        name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
        var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
        var results = regex.exec(location.search);
        return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
    };
    // Adding title and id to fields
    if (getUrlParameter('simdiaw_p_id') != '' && getUrlParameter('simdiaw_p_title') != '') {
        var pId = getUrlParameter('simdiaw_p_id');
        var pTitle = decodeURI(getUrlParameter('simdiaw_p_title'));
        jQuery("#title").val(pTitle);
        jQuery("#simdiaw-link-text").val(pTitle);
        jQuery("#simdiaw-art-id option[value='" + pId + "']").attr('selected', 'selected');
   }
});