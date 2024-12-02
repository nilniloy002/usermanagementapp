<?php

namespace Vanguard\Http\Controllers\Web;

use Vanguard\MockTestStatus;
use Vanguard\Http\Requests\MockTestStatus\CreateMockTestStatusRequest;
use Vanguard\Http\Requests\MockTestStatus\UpdateMockTestStatusRequest;
use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;

class MockTestStatusController extends Controller
{
    public function index()
    {
        $mockTestStatuses = MockTestStatus::orderBy('id', 'asc')->paginate(10);
        return view('mockteststatuses.index', compact('mockTestStatuses'));
    }

    public function create()
    {
        return view('mockteststatuses.add-edit');
    }

    public function store(CreateMockTestStatusRequest $request)
    {
        MockTestStatus::create($request->validated());
        return redirect()->route('mock_test_statuses.index')->withSuccess(__('Mock Test Status created successfully.'));
    }

    public function edit(MockTestStatus $mockTestStatus)
    {
        return view('mockteststatuses.add-edit', compact('mockTestStatus'));
    }

    public function update(UpdateMockTestStatusRequest $request, MockTestStatus $mockTestStatus)
    {
        $mockTestStatus->update($request->validated());
        return redirect()->route('mock_test_statuses.index')->withSuccess(__('Mock Test Status updated successfully.'));
    }

    public function destroy(MockTestStatus $mockTestStatus)
    {
        $mockTestStatus->delete();
        return redirect()->route('mock_test_statuses.index')->withSuccess(__('Mock Test Status deleted successfully.'));
    }
}
