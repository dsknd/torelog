<?php

namespace App\Enums;

enum ExerciseEnum: string
{
    // 胸筋
    case BENCH_PRESS = 'bench_press';
    case INCLINE_BENCH_PRESS = 'incline_bench_press';
    case DUMBBELL_PRESS = 'dumbbell_press';
    case PUSH_UPS = 'push_ups';
    case CHEST_FLY = 'chest_fly';

    // 背中
    case PULL_UPS = 'pull_ups';
    case LAT_PULLDOWN = 'lat_pulldown';
    case BARBELL_ROW = 'barbell_row';
    case DUMBBELL_ROW = 'dumbbell_row';
    case DEADLIFT = 'deadlift';

    // 肩
    case OVERHEAD_PRESS = 'overhead_press';
    case LATERAL_RAISE = 'lateral_raise';
    case REAR_DELT_FLY = 'rear_delt_fly';
    case UPRIGHT_ROW = 'upright_row';

    // 腕
    case BICEP_CURL = 'bicep_curl';
    case HAMMER_CURL = 'hammer_curl';
    case TRICEP_DIPS = 'tricep_dips';
    case TRICEP_EXTENSION = 'tricep_extension';

    // 脚
    case SQUAT = 'squat';
    case FRONT_SQUAT = 'front_squat';
    case ROMANIAN_DEADLIFT = 'romanian_deadlift';
    case LEG_PRESS = 'leg_press';
    case LUNGES = 'lunges';
    case CALF_RAISE = 'calf_raise';

    // 体幹
    case PLANK = 'plank';
    case CRUNCHES = 'crunches';
    case RUSSIAN_TWIST = 'russian_twist';
    case MOUNTAIN_CLIMBER = 'mountain_climber';

    public function getName(): string
    {
        return match($this) {
            self::BENCH_PRESS => 'ベンチプレス',
            self::INCLINE_BENCH_PRESS => 'インクラインベンチプレス',
            self::DUMBBELL_PRESS => 'ダンベルプレス',
            self::PUSH_UPS => '腕立て伏せ',
            self::CHEST_FLY => 'チェストフライ',
            
            self::PULL_UPS => '懸垂',
            self::LAT_PULLDOWN => 'ラットプルダウン',
            self::BARBELL_ROW => 'バーベルロー',
            self::DUMBBELL_ROW => 'ダンベルロー',
            self::DEADLIFT => 'デッドリフト',
            
            self::OVERHEAD_PRESS => 'オーバーヘッドプレス',
            self::LATERAL_RAISE => 'ラテラルレイズ',
            self::REAR_DELT_FLY => 'リアデルトフライ',
            self::UPRIGHT_ROW => 'アップライトロー',
            
            self::BICEP_CURL => 'バイセップカール',
            self::HAMMER_CURL => 'ハンマーカール',
            self::TRICEP_DIPS => 'トライセップディップス',
            self::TRICEP_EXTENSION => 'トライセップエクステンション',
            
            self::SQUAT => 'スクワット',
            self::FRONT_SQUAT => 'フロントスクワット',
            self::ROMANIAN_DEADLIFT => 'ルーマニアンデッドリフト',
            self::LEG_PRESS => 'レッグプレス',
            self::LUNGES => 'ランジ',
            self::CALF_RAISE => 'カーフレイズ',
            
            self::PLANK => 'プランク',
            self::CRUNCHES => 'クランチ',
            self::RUSSIAN_TWIST => 'ロシアンツイスト',
            self::MOUNTAIN_CLIMBER => 'マウンテンクライマー',
        };
    }

    public function getDescription(): string
    {
        return match($this) {
            self::BENCH_PRESS => 'バーベルを使った基本的な胸筋トレーニング',
            self::INCLINE_BENCH_PRESS => '上部胸筋を重点的に鍛えるベンチプレス',
            self::DUMBBELL_PRESS => 'ダンベルを使った胸筋トレーニング',
            self::PUSH_UPS => '自重を使った胸筋トレーニング',
            self::CHEST_FLY => '胸筋の分離を重視したトレーニング',
            
            self::PULL_UPS => '上半身の引く力を鍛える自重トレーニング',
            self::LAT_PULLDOWN => 'マシンを使った背中のトレーニング',
            self::BARBELL_ROW => 'バーベルを使った背中のトレーニング',
            self::DUMBBELL_ROW => 'ダンベルを使った背中のトレーニング',
            self::DEADLIFT => '全身を使った代表的なコンパウンド種目',
            
            self::OVERHEAD_PRESS => '肩全体を鍛える基本種目',
            self::LATERAL_RAISE => '三角筋中部を集中的に鍛える種目',
            self::REAR_DELT_FLY => '三角筋後部を鍛える種目',
            self::UPRIGHT_ROW => '肩と僧帽筋を鍛える種目',
            
            self::BICEP_CURL => '上腕二頭筋の基本種目',
            self::HAMMER_CURL => '上腕二頭筋と前腕を鍛える種目',
            self::TRICEP_DIPS => '上腕三頭筋の自重トレーニング',
            self::TRICEP_EXTENSION => '上腕三頭筋の分離種目',
            
            self::SQUAT => '下半身の王道トレーニング',
            self::FRONT_SQUAT => '大腿四頭筋を重点的に鍛えるスクワット',
            self::ROMANIAN_DEADLIFT => 'ハムストリングと臀筋を鍛える種目',
            self::LEG_PRESS => 'マシンを使った下半身トレーニング',
            self::LUNGES => '片脚ずつ行う下半身トレーニング',
            self::CALF_RAISE => 'ふくらはぎを鍛える種目',
            
            self::PLANK => '体幹全体を鍛える基本種目',
            self::CRUNCHES => '腹筋の基本種目',
            self::RUSSIAN_TWIST => '腹斜筋を鍛える回転運動',
            self::MOUNTAIN_CLIMBER => '全身を使った有酸素性の体幹トレーニング',
        };
    }

    public function getPrimaryMuscles(): array
    {
        return match($this) {
            self::BENCH_PRESS, self::INCLINE_BENCH_PRESS, 
            self::DUMBBELL_PRESS, self::PUSH_UPS, self::CHEST_FLY => [MuscleEnum::CHEST],
            
            self::PULL_UPS, self::LAT_PULLDOWN => [MuscleEnum::LATS],
            self::BARBELL_ROW, self::DUMBBELL_ROW => [MuscleEnum::LATS, MuscleEnum::RHOMBOIDS],
            self::DEADLIFT => [MuscleEnum::LOWER_BACK, MuscleEnum::GLUTES, MuscleEnum::HAMSTRINGS],
            
            self::OVERHEAD_PRESS => [MuscleEnum::SHOULDERS],
            self::LATERAL_RAISE => [MuscleEnum::SHOULDERS],
            self::REAR_DELT_FLY => [MuscleEnum::REAR_DELTS],
            self::UPRIGHT_ROW => [MuscleEnum::SHOULDERS, MuscleEnum::MIDDLE_TRAPS],
            
            self::BICEP_CURL, self::HAMMER_CURL => [MuscleEnum::BICEPS],
            self::TRICEP_DIPS, self::TRICEP_EXTENSION => [MuscleEnum::TRICEPS],
            
            self::SQUAT, self::FRONT_SQUAT, self::LEG_PRESS => [MuscleEnum::QUADRICEPS, MuscleEnum::GLUTES],
            self::ROMANIAN_DEADLIFT => [MuscleEnum::HAMSTRINGS, MuscleEnum::GLUTES],
            self::LUNGES => [MuscleEnum::QUADRICEPS, MuscleEnum::GLUTES],
            self::CALF_RAISE => [MuscleEnum::CALVES],
            
            self::PLANK => [MuscleEnum::ABS, MuscleEnum::LOWER_BACK],
            self::CRUNCHES => [MuscleEnum::ABS],
            self::RUSSIAN_TWIST => [MuscleEnum::OBLIQUES],
            self::MOUNTAIN_CLIMBER => [MuscleEnum::ABS, MuscleEnum::SHOULDERS],
        };
    }

    public function getMuscleGroupCategories(): array
    {
        $primaryMuscles = $this->getPrimaryMuscles();
        $categories = [];
        
        foreach ($primaryMuscles as $muscle) {
            $category = $muscle->getMuscleGroupCategory();
            if (!in_array($category, $categories)) {
                $categories[] = $category;
            }
        }
        
        return $categories;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'description' => $this->getDescription(),
        ];
    }
}