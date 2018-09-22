<h1>JHL Auto Login Settings</h1>

<form class="jhl-ul" method="POST">
    <?php echo wp_nonce_field( 'jhl_al_option_page_update' ); ?>

    <div>
        <label for="jhl_al_enable"><strong>Enable Auto Login</strong></label>
        <?php $enabled = get_option( 'jhl_al_enable', 0 ); ?>
        <label style="width:auto;"><input type="radio" name="jhl_al_enable" id="jhl_al_enable" value="1" <?php echo ( $enabled == 1 ) ? 'checked="checked"' : ''; ?>> Yes</label>
        &nbsp;&nbsp;&nbsp;
        <label style="width:auto;"><input type="radio" name="jhl_al_enable" id="jhl_al_enable" value="0" <?php echo ( $enabled == 0 ) ? 'checked="checked"' : ''; ?>> No</label>
    </div>

    <!-- <div style="display:inline-block;"> -->
    <div>
        <div style="width:230px;display:inline-block;float:left;">
            <label for="jhl_al_add_user_fullname"><strong>Select a User</strong></label>
        </div>
        <div style="display:inline-block;">
            <?php
            $login_user_id = get_option( 'jhl_al_user', 0 );
            // select users
            $users = get_users();
            ?>
            <select name="jhl_al_user" id="jhl_al_user">
                <option value="">No one</option>
                <?php foreach( $users as $user ) { ?>
                    <option value="<?php echo $user->ID; ?>" <?php echo ( $login_user_id == $user->ID ) ? 'selected="selected"' : ''; ?>><?php echo $user->display_name; ?></option>
                <?php } ?>
            </select>
            <br>
            <small>Please don't use this part, use the part below</small>
        </div>
    </div>

    <div><strong>- or -</strong></div>

    <div>
        <div style="width:230px;display:inline-block;float:left;">
            <label for="jhl_al_meta_key"><strong>User Meta Key</strong></label>
        </div>
        <div style="display:inline-block;">
            <?php
            $jhl_al_meta_key = get_option( 'jhl_al_meta_key', '' );
            ?>
            <input type="text" name="jhl_al_meta_key" id="jhl_al_meta_key" value="<?php echo $jhl_al_meta_key; ?>" placeholder="invite_hash">
            <br>
            <small>Unless you're writing custom code, you should use <strong>invite_hash</strong></small>
        </div>
    </div>
    <div>
        <div style="width:230px;display:inline-block;float:left;">
            <label for="jhl_al_query_string_paramter"><strong>Query String Parameter</strong></label>
        </div>
        <div style="display:inline-block;">
            <?php
            $jhl_al_query_string_paramter = get_option( 'jhl_al_query_string_paramter', '' );
            ?>
            <input type="text" name="jhl_al_query_string_paramter" id="jhl_al_query_string_paramter" value="<?php echo $jhl_al_query_string_paramter; ?>" placeholder="invite">
            <br>
            <small>This is the key in the querystring, like ?<strong>invite</strong>=&lt;whatever key you made on the user profile page&gt;</small>
        </div>
    </div>

    <div style="clear:both;"></div>

    <input type="submit" value="Save" class="button button-primary button-large">

    <style>
    form.jhl-ul > div {
        padding-bottom : 15px;
    }
    form.jhl-ul label {
        display: inline-block;
        width: 230px;
    }
    </style>
</form>
