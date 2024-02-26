<?php

namespace App\Orchid\Screens;

use App\Models\Task;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;

class TaskScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'tasks' => Task::latest()->get(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Day la To-do List theo DOC.';
    }

    /**
     * The description is displayed on the user's screen under the heading
     */
    public function description(): ?string
    {
        return 'Ai biết gì đâu, DOC kêu làm vậy.';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('Add Task')
                ->modal('taskModal')
                ->method('create')
                ->icon('plus'),
        ];
    }

    /**
     * The screen's layout elements. Có thể thêm nhiều thành phần cần hiển thị trên layout màn hình Screen.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::table('tasks', [
                TD::make('name'),
                TD::make('Actions')
            ->alignRight()
            ->render(function (Task $task) {
                return Button::make('Delete Task')
                    ->confirm('After deleting, the task will be gone forever.')
                    ->method('delete', ['task' => $task->id]);
            }),
            ]),
            Layout::modal('taskModal', Layout::rows([
                Input::make('task.name')
                    ->title('Tên Task')
                    ->placeholder('Nhập tên task đê')
                    ->help('Tên task được tạo, thích nói vậy đó.'),
            ]))
                ->title('Tạo Task')
                ->applyButton('Thêm Task'),
        ];
    }

    /**
     * Func tạo như bên service cũ
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function create(Request $request)
    {
        // Validate form data, save task to database, etc.
        $request->validate([
            'task.name' => 'required|max:255',
        ]);

        $task = new Task();
        $task->name = $request->input('task.name');
        $task->save();
    }

    /**
     * @param Task $task
     *
     * @return void
     */
    public function delete(Task $task)
    {
        $task->delete();
    }
}
