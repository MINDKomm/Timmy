<?php

use Timber\ImageHelper;
use Timber\Timber;

/**
 * Class TestTimmy
 */
class TestTimmy extends TimmyUnitTestCase {
	/**
	 * A single example test.
	 */
	public function test_sample() {
		$this->assertTrue( true );
	}

	public function test_create_image() {
		$attachment = $this->create_image();

		$src  = Timber::compile_string( '{{ img.src }}', [ 'img' => $attachment ] );
		$path = ImageHelper::get_server_location( $src );

		$this->assertFileExists( $path );
	}

	public function test_twig_lazy_filter() {
		$attachment = $this->create_image();
		$context    = [
			'size'  => 'large',
			'image' => $attachment,
		];

		// Default, srcset only.
		$result   = Timber::compile_string(
			'{{ image|get_timber_image_responsive(size)|lazy }}',
			$context
		);
		$expected = sprintf(
			' data-srcset="%1$s/test-560x0-c-default.jpg 560w, %1$s/test-1400x0-c-default.jpg 1400w" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" sizes="100vw" alt=""',
			$this->get_upload_url()
		);

		$this->assertEquals( $expected, $result );

		// Srcset and src.
		$result   = Timber::compile_string(
			"{{ image|get_timber_image_responsive(size)|lazy(['srcset', 'src']) }}",
			$context
		);
		$expected = sprintf(
			' data-srcset="%1$s/test-560x0-c-default.jpg 560w, %1$s/test-1400x0-c-default.jpg 1400w" data-src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" sizes="100vw" alt=""',
			$this->get_upload_url()
		);

		$this->assertEquals( $expected, $result );

		// Srcset, src and sizes.
		$result   = Timber::compile_string(
			"{{ image|get_timber_image_responsive(size)|lazy(['srcset', 'src', 'sizes']) }}",
			$context
		);
		$exptected = sprintf(
			' data-srcset="%1$s/test-560x0-c-default.jpg 560w, %1$s/test-1400x0-c-default.jpg 1400w" data-src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" data-sizes="100vw" alt=""',
			$this->get_upload_url()
		);

		$this->assertEquals( $exptected, $result );
	}
}
