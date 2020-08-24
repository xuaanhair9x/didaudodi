<?php
 
// Do not delete these lines
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
die ('Please do not load this page directly. Thanks!');
 
if ( post_password_required() ) { ?>
<p class="nocomments"><?php _e('This post is password protected. Enter the password to view comments.', MTS_THEME_TEXTDOMAIN ); ?></p>
<?php
return;
}
?>
<!-- You can start editing here. -->
<?php if ( have_comments() ) : ?>
	<div id="comments">
		<h4 class="total-comments"><?php comments_number(__('No Comments', MTS_THEME_TEXTDOMAIN ), __('Comments<span>1</span>', MTS_THEME_TEXTDOMAIN ),  __('Comments<span>%</span>', MTS_THEME_TEXTDOMAIN ) );?></h4>
			<ol class="commentlist">
				<div class="navigation">
					<div class="alignleft"><?php previous_comments_link() ?></div>
					<div class="alignright"><?php next_comments_link() ?></div>
				</div>
				<?php wp_list_comments('type=comment&callback=mts_comments'); ?>
				<div class="navigation">
					<div class="alignleft"><?php previous_comments_link() ?></div>
					<div class="alignright"><?php next_comments_link() ?></div>
				</div>
			</ol>
		</div>
<?php else : // this is displayed if there are no comments so far ?>
<?php if ('open' == $post->comment_status) : ?>
<!-- If comments are open, but there are no comments. -->
<?php else : // comments are closed ?>
<!-- If comments are closed. -->
<p class="nocomments"></p>
<?php endif; ?>
<?php endif; ?>

<?php if ('open' == $post->comment_status) : ?>
	<div id="commentsAdd">
		<div id="respond" class="box m-t-6">
			<?php global $aria_req; 
			$consent  = empty( $commenter['comment_author_email'] ) ? '' : ' checked="checked"';
			$comments_args = array(
					'title_reply'=>'<h4>'.__('Add your comment', MTS_THEME_TEXTDOMAIN ).'</h4>',
					'comment_notes_before' => '',
					'comment_notes_after' => '',
					'label_submit' => __('Post Comment', MTS_THEME_TEXTDOMAIN),
					'comment_field' => '<p class="comment-form-comment"><label for="comment" class="comment-label">'.__('Your Comment', MTS_THEME_TEXTDOMAIN ).'</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>',
					'fields' => apply_filters( 'comment_form_default_fields',
						array(
							'author' => '<p class="comment-form-author">'
							.( $req ? '' : '' ).'<label for="author" class="comment-label">'.__('Your Name', MTS_THEME_TEXTDOMAIN ).'</label><input id="author" name="author" type="text" value="'.esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></p>',
							'email' => '<p class="comment-form-email">'
							.($req ? '' : '' ) . '<label for="email" class="comment-label">'.__('Email Address', MTS_THEME_TEXTDOMAIN ).'</label><input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ).'" size="30"'.$aria_req.' /></p>',
							'url' => '<p class="comment-form-url"><label for="url" class="comment-label">'.__('Website', MTS_THEME_TEXTDOMAIN ).'</label><input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></p>',
						) 
					)
				); 
			comment_form($comments_args); ?>
		</div>
	</div>
<?php endif; // if you delete this the sky will fall on your head ?>
