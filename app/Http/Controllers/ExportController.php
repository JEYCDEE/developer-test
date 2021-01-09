<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\ConvertTypesEnum;
use App\Exceptions\FileConverterException;
use App\Services\FileConverter;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ExportController
 * @package App\Http\Controllers
 */
class ExportController extends Controller
{
    private FileConverter $converter;

    /**
     * ExportController constructor.
     * @param FileConverter $converter
     */
    public function __construct(FileConverter $converter)
    {
        $this->converter = $converter;
    }

    /**
     * Converts the user input into a CSV file and streams the file back to the user
     * @param Request $request
     * @return JsonResponse
     */
    public function csv(Request $request): JsonResponse
    {
        try {
            $data = $request->request->get('data');
            $csvConverter = $this->converter->get(ConvertTypesEnum::CSV_TYPE, $data);
            $downloadLink = $csvConverter->convert();

            return $this->successResponse(['downloadLink' => $downloadLink]);
        } catch (FileConverterException $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        } catch (Exception $e) {
            return $this->generalErrorResponse();
        }
    }
}
