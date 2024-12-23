<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTestRequest;
use App\Http\Requests\UpdateTestRequest;
use App\Http\Resources\TestResource;
use App\Repositery\TestRepository;
use Illuminate\Http\Request;

class TestController extends Controller
{
    protected $TestRepo;

    public function __construct(TestRepository $TestRepo)
    {
        $this->TestRepo = $TestRepo;
    }

    // List all test with pagination
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 20);
        $test = $this->TestRepo->getAll($perPage);

        return TestResource::collection($test);
    }

    // Show a single test
    public function show($id)
    {
        $test = $this->TestRepo->findById($id);
        if (!$test) {
            return response()->json(['message' => 'test not found'], 404);
        }
        return new TestResource($test);
    }

    // Create a new test
    public function store(StoreTestRequest $request)
    {
        try {
            $data = $request->validated();

            $test = $this->TestRepo->create($data);

            return response()->json(['message' => 'test created successfully.'], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Update an existing test
    public function update(UpdateTestRequest $request, $id)
    {
        try {
            $test = $this->TestRepo->findById($id);

            if (!$test) {
                return response()->json(['message' => 'test not found'], 404);
            }

            $data = $request->validated();

            $updatedCountry = $this->TestRepo->update($test, $data);

            return response()->json(['message' => 'test Updated successfully.'], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Delete a test
    public function destroy($id)
    {
        $test = $this->TestRepo->findById($id);

        if (!$test) {
            return response()->json([
                'message' => 'test not found.',
                'errors' => [
                    'id' => ['The specified test does not exist.']
                ]
            ], 404);
        }

        $this->TestRepo->delete($test);

        return response()->json(['message' => 'test deleted successfully.'], 200);
    }
}
