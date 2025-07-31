<?php
$data = [
    'model' => 'z-ai/glm-4.5-air:free',
    'messages' => [
        ['role' => 'user', 'content' => "test"]
    ]
];
echo json_encode($data);