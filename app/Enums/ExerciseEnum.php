<?php

namespace App\Enums;

enum ExerciseEnum: string
{
    // 胸筋
    case BENCH_PRESS = 'bench_press';
    case DUMBBELL_PRESS = 'dumbbell_press';
    case PUSH_UPS = 'push_ups';

    // 背中
    case PULL_UPS = 'pull_ups';
    case LAT_PULLDOWN = 'lat_pulldown';
    case BARBELL_ROW = 'barbell_row';
    case DEADLIFT = 'deadlift';

    // 脚
    case SQUAT = 'squat';
    case LEG_PRESS = 'leg_press';
    case LUNGES = 'lunges';
    case LEG_CURL = 'leg_curl';
    case CALF_RAISE = 'calf_raise';

    // 肩
    case OVERHEAD_PRESS = 'overhead_press';
    case DUMBBELL_SHOULDER_PRESS = 'dumbbell_shoulder_press';
    case LATERAL_RAISE = 'lateral_raise';

    // 腕
    case BICEP_CURL = 'bicep_curl';
    case TRICEP_EXTENSION = 'tricep_extension';

    // 腹筋
    case PLANK = 'plank';
    case CRUNCHES = 'crunches';

    public function getName(): string
    {
        return match ($this) {
            self::BENCH_PRESS => 'ベンチプレス',
            self::DUMBBELL_PRESS => 'ダンベルプレス',
            self::PUSH_UPS => '腕立て伏せ',
            self::PULL_UPS => '懸垂',
            self::LAT_PULLDOWN => 'ラットプルダウン',
            self::BARBELL_ROW => 'バーベルロー',
            self::DEADLIFT => 'デッドリフト',
            self::SQUAT => 'スクワット',
            self::LEG_PRESS => 'レッグプレス',
            self::LUNGES => 'ランジ',
            self::LEG_CURL => 'レッグカール',
            self::CALF_RAISE => 'カーフレイズ',
            self::OVERHEAD_PRESS => 'オーバーヘッドプレス',
            self::DUMBBELL_SHOULDER_PRESS => 'ダンベルショルダープレス',
            self::LATERAL_RAISE => 'ラテラルレイズ',
            self::BICEP_CURL => 'バイセップカール',
            self::TRICEP_EXTENSION => 'トライセップエクステンション',
            self::PLANK => 'プランク',
            self::CRUNCHES => 'クランチ',
        };
    }

    public function getDescription(): string
    {
        return match ($this) {
            self::BENCH_PRESS => 'バーベルを使った基本的な胸筋トレーニング',
            self::DUMBBELL_PRESS => 'ダンベルを使った胸筋トレーニング',
            self::PUSH_UPS => '自重を使った胸筋トレーニング',
            self::PULL_UPS => '上半身の引く力を鍛える自重トレーニング',
            self::LAT_PULLDOWN => 'マシンを使った背中のトレーニング',
            self::BARBELL_ROW => 'バーベルを使った背中のトレーニング',
            self::DEADLIFT => '全身を使った代表的なコンパウンド種目',
            self::SQUAT => '下半身の王道トレーニング',
            self::LEG_PRESS => 'マシンを使った下半身トレーニング',
            self::LUNGES => '片脚ずつ行う下半身トレーニング',
            self::LEG_CURL => 'ハムストリングを集中的に鍛えるマシン種目',
            self::CALF_RAISE => 'ふくらはぎを鍛える種目',
            self::OVERHEAD_PRESS => '肩全体を鍛える基本種目',
            self::DUMBBELL_SHOULDER_PRESS => 'ダンベルで行う肩プレス',
            self::LATERAL_RAISE => '三角筋中部を集中的に鍛える種目',
            self::BICEP_CURL => '上腕二頭筋の基本種目',
            self::TRICEP_EXTENSION => '上腕三頭筋の分離種目',
            self::PLANK => '体幹全体を鍛える基本種目',
            self::CRUNCHES => '腹筋の基本種目',
        };
    }

    public function getPrimaryMuscles(): array
    {
        return match ($this) {
            self::BENCH_PRESS, self::DUMBBELL_PRESS, self::PUSH_UPS => [MuscleEnum::PECTORALIS_MAJOR],
            self::PULL_UPS, self::LAT_PULLDOWN => [MuscleEnum::LATISSIMUS_DORSI],
            self::BARBELL_ROW => [MuscleEnum::LATISSIMUS_DORSI, MuscleEnum::RHOMBOIDS],
            self::DEADLIFT => [MuscleEnum::ERECTOR_SPINAE, MuscleEnum::GLUTES, MuscleEnum::HAMSTRINGS],
            self::SQUAT, self::LEG_PRESS => [MuscleEnum::QUADRICEPS, MuscleEnum::GLUTES],
            self::LUNGES => [MuscleEnum::QUADRICEPS, MuscleEnum::GLUTES],
            self::LEG_CURL => [MuscleEnum::HAMSTRINGS],
            self::CALF_RAISE => [MuscleEnum::CALVES],
            self::OVERHEAD_PRESS, self::DUMBBELL_SHOULDER_PRESS => [MuscleEnum::ANTERIOR_DELTOID],
            self::LATERAL_RAISE => [MuscleEnum::LATERAL_DELTOID],
            self::BICEP_CURL => [MuscleEnum::BICEPS_BRACHII],
            self::TRICEP_EXTENSION => [MuscleEnum::TRICEPS_BRACHII],
            self::PLANK => [MuscleEnum::RECTUS_ABDOMINIS, MuscleEnum::ERECTOR_SPINAE],
            self::CRUNCHES => [MuscleEnum::RECTUS_ABDOMINIS],
        };
    }

    public function getMuscleGroupCategories(): array
    {
        $primaryMuscles = $this->getPrimaryMuscles();
        $categories = [];

        foreach ($primaryMuscles as $muscle) {
            $category = $muscle->getMuscleGroupCategory();
            if (! in_array($category, $categories)) {
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
