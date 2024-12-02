<?php

namespace Vanguard\Http\Controllers\Web;

use Vanguard\MockTestRoom;
use Vanguard\Http\Requests\MockTestRoom\CreateMockTestRoomRequest;
use Vanguard\Http\Requests\MockTestRoom\UpdateMockTestRoomRequest;
use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;

class MockTestRoomController extends Controller
{
    public function index()
    {
        $mockTestRooms = MockTestRoom::paginate(10);
        return view('mocktestrooms.index', compact('mockTestRooms'));
    }

    public function create()
    {
        return view('mocktestrooms.add-edit', ['edit' => false]);
    }

    public function store(CreateMockTestRoomRequest $request)
    {
        MockTestRoom::create($request->validated());
        return redirect()->route('mock_test_rooms.index')->with('success', __('Mock Test Room created successfully.'));
    }

    public function edit(MockTestRoom $mockTestRoom)
    {
        return view('mocktestrooms.add-edit', ['mockTestRoom' => $mockTestRoom, 'edit' => true]);
    }

    public function update(UpdateMockTestRoomRequest $request, MockTestRoom $mockTestRoom)
    {
        $mockTestRoom->update($request->validated());
        return redirect()->route('mock_test_rooms.index')->with('success', __('Mock Test Room updated successfully.'));
    }

    public function destroy(MockTestRoom $mockTestRoom)
    {
        $mockTestRoom->delete();
        return redirect()->route('mock_test_rooms.index')->with('success', __('Mock Test Room deleted successfully.'));
    }
}