<?php
namespace Tests\Functional;

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\Environment;

/**
 * This is an example class that shows how you could set up a method that
 * runs the application.
 * Note that it doesn't cover all use-cases and is
 * tuned to the specifics of this skeleton app, so if your needs are
 * different, you'll need to change it.
 */
class BaseTestCase extends \PHPUnit_Framework_TestCase
{

	/**
	 * Use middleware when running application?
	 *
	 * @var bool
	 */
	protected $withMiddleware = false;

	/**
	 * Process the application given a request method and URI
	 *
	 * @param string $requestMethod
	 *        	the request method (e.g. GET, POST, etc.)
	 * @param string $requestUri
	 *        	the request URI
	 * @param array|object|null $requestData
	 *        	the request data
	 * @return \Slim\Http\Response
	 */
	public function runApp($requestMethod, $requestUri, $requestData = null)
	{
		// Create a mock environment for testing with
		$environment = Environment::mock([
			'REQUEST_METHOD' => $requestMethod,
			'REQUEST_URI' => $requestUri
		]);
		
		// Set up a request object based on the environment
		$request = Request::createFromEnvironment($environment);
		
		// Add request data, if it exists
		if (isset($requestData)) {
			$request = $request->withParsedBody($requestData);
		}
		
		// Set up a response object
		$response = new Response();
		
		// Use the application settings
		$settings = require __DIR__ . '/../../src/settings.php';
		
		// Instantiate the application
		$app = new App($settings);
		
		// Set up dependencies
		require __DIR__ . '/../../src/dependencies.php';
		
		// Register utils
		require_once __DIR__ . '/../../src/utils/JWT.php';
		require_once __DIR__ . '/../../src/utils/Responses.php';
		require_once __DIR__ . '/../../src/utils/Security.php';
		require_once __DIR__ . '/../../src/utils/Generic.php';
		require_once __DIR__ . '/../../src/utils/BrowserDetection.php';
				
		// Register models
		require_once __DIR__ . '/../../src/models/Connect.php';
		require_once __DIR__ . '/../../src/models/DynamicModel.php';
		require_once __DIR__ . '/../../src/models/UserModel.php';
		require_once __DIR__ . '/../../src/models/AuditModel.php';
		require_once __DIR__ . '/../../src/models/ForumModel.php';
		require_once __DIR__ . '/../../src/models/CourseModel.php';

		// Register middleware
		if ($this->withMiddleware) {
			require_once __DIR__ . '/../../src/middlewares/AuthMiddleware.php';
			require_once __DIR__ . '/../../src/middlewares/RegisterEventMiddleware.php';
			require __DIR__ . '/../../src/middleware.php';
		}
		
		// Register routes
		require __DIR__ . '/../../src/routes.php';
		require __DIR__ . '/../../src/controllers/DynamicController.php';
		require __DIR__ . '/../../src/controllers/AuthController.php';
		require __DIR__ . '/../../src/controllers/ForumController.php';
		require __DIR__ . '/../../src/controllers/CourseController.php';
		
		// Process the application
		$response = $app->process($request, $response);
		
		// Return the response
		return $response;
	}
}
