<?php
namespace MemoryLane;
class TaskComment extends AbstractEntity {
    protected static $table = 'task_comments';
    protected static $editable_columns = ["text"];
}