                    <form method="post" action="#" id="j-up-profile">
                        <div class="user-profile-container">
                            <div class="edit-email">
                                <a  href="javascript:void(0);" class="edit-email__link">
                                    <?php esc_html_e('Edit Email Address','j-data'); ?>
                                </a>
                                <div class="edit-email__field form-element-container hideit">
                                     <input type="email" name="user_email_value" value="<?php echo $user_email;?>" class="form-element"/>
                                </div>
                            </div>

                            <div class="weekly-mail form-element-container">
                                <input type="checkbox" name="weekly_mail" value="1" class="form-element" id="weekly_mail" <?php echo $weekly_mail ? "checked='checked'" : "";?> /><label for="weekly_mail"><?php esc_html_e('Get Weekly Gain Notifications on Mail','j-data'); ?></label>
                            </div>

                            <div class="weekly-notify form-element-container">
                                <input type="checkbox" name="weekly_push_notification" value="1" class="form-element" id="weekly_push_notification" <?php echo $weekly_push_notification ? "checked='checked'" : "";?> /><label for="weekly_push_notification"><?php esc_html_e('Get Weekly Gain Push Notifications on your Mobile Phone','j-data'); ?></label>
                            </div>

                            <div class="monthly-mail form-element-container">
                                <input type="checkbox" name="monthly_mail" value="1" class="form-element" id="monthly_mail" <?php echo $monthly_mail ? "checked='checked'" : "";?> ><label for="monthly_mail"><?php esc_html_e('Get Monthly Gain Report on mail','j-data'); ?></label>
                            </div>                            

                        </div>
                        <div class="btn-loading-container">
                        <button type="submit" class="form-submit-btn"><?php esc_html_e('Update','j-data'); ?></button>
                            <img src="<?php echo JOURNAL_PLUGIN_URL."img/reload.svg"?>" class="animate-svg" alt="triangle with all three sides equal" width="20" />
                        </div>
                        <div class="toast-msg-container">
                    
                        </div>
                  </form>

                  <div class="user-logout-link">
                  <?php
                    printf('<a class="form-submit-btn" href="%1$s">%2$s</a>', wp_logout_url(home_url()) , esc_html__( "Logout", "j-data" )); 
                  ?>
                  </div>

                  