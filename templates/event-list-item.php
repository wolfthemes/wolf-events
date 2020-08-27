<?php
/**
 * Template to render the event in the event list.
 *
 * @author WolfThemes
 * @category Core
 * @package WolfEvents/Admin
 * @version 1.2.2
 */
?>
<div class="<?php echo esc_attr( $classes );  ?>" itemscope itemtype="http://schema.org/MusicEvent">
	<?php
		/**
		 * we_event_list_item_start hook
		 */
		do_action( 'we_event_list_item_start' );
	?>
	<meta itemprop="name" content="<?php echo esc_attr( $name ); ?>">
	<meta itemprop="url" content="<?php echo esc_url( $permalink ); ?>">
	<?php if ( $thumbnail_url ) : ?>
		<meta itemprop="image" content="<?php echo esc_url( $thumbnail_url ); ?>">
	<?php endif; ?>
	<meta itemprop="description" content="<?php echo esc_attr( $description ); ?>">
	<div class="we-table-cell we-date" itemprop="startDate" content="<?php echo esc_attr( $raw_start_date ); ?>">
		<?php if ( $formatted_start_date ) : ?>
			<?php echo we_sanitize_date( $formatted_start_date ); ?>
		<?php endif; ?>
	</div><!-- .we-date -->
	<div class="we-table-cell we-location" itemprop="location" itemscope itemtype="http://schema.org/MusicVenue">
		<span itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
			<?php if ( $city ) : ?>
				<meta itemprop="addressLocality" content="<?php echo esc_attr( $city ); ?>">
			<?php endif; ?>

			<?php if ( $address ) : ?>
				<meta itemprop="streetAddress" content="<?php echo esc_attr( $address ); ?>">
			<?php endif; ?>

			<?php if ( $state ) : ?>
				<meta itemprop="addressRegion" content="<?php echo esc_attr( $state ); ?>">
			<?php endif; ?>

			<?php if ( $zipcode ) : ?>
				<meta itemprop="postalCode" content="<?php echo esc_attr( $zipcode ); ?>">
			<?php endif; ?>
		</span>

		<?php if ( $link ) : ?>
			<a rel="bookmark" class="entry-link" href="<?php the_permalink(); ?>">
		<?php endif; ?>
			<span itemprop="name" class="we-venue"><?php echo sanitize_text_field( $venue ); ?></span>

			<span class="we-display-location"><?php echo sanitize_text_field( $display_location ); ?></span>

		<?php if ( $link ) : ?>
			</a>
		<?php endif; ?>
	</div><!-- .we-location -->
	<div class="we-table-cell we-action">
		<?php if ( $action ) : ?>
			<?php echo we_sanitize_action( $action ); ?>
		<?php endif; ?>
	</div><!-- .we-action -->
	<?php
		/**
		 * we_event_list_item_end hook
		 */
		do_action( 'we_event_list_item_end' );
	?>
</div><!-- .we-list-event -->
