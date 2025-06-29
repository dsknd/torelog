<?php

namespace App\Enums;

enum ExerciseEnum: string
{
    // 胸筋
    case BENCH_PRESS = 'bench_press';
    case INCLINE_BENCH_PRESS = 'incline_bench_press';
    case DECLINE_BENCH_PRESS = 'decline_bench_press';
    case CLOSE_GRIP_BENCH_PRESS = 'close_grip_bench_press';
    case DUMBBELL_PRESS = 'dumbbell_press';
    case INCLINE_DUMBBELL_PRESS = 'incline_dumbbell_press';
    case DECLINE_DUMBBELL_PRESS = 'decline_dumbbell_press';
    case DUMBBELL_FLY = 'dumbbell_fly';
    case INCLINE_DUMBBELL_FLY = 'incline_dumbbell_fly';
    case DUMBBELL_PULLOVER = 'dumbbell_pullover';
    case CHEST_PRESS_MACHINE = 'chest_press_machine';
    case PEC_DECK_FLY = 'pec_deck_fly';
    case CABLE_CROSSOVER = 'cable_crossover';
    case CABLE_FLY_HIGH = 'cable_fly_high';
    case CABLE_FLY_MID = 'cable_fly_mid';
    case CABLE_FLY_LOW = 'cable_fly_low';
    case PUSH_UPS = 'push_ups';
    case DIPS = 'dips';
    case DIAMOND_PUSH_UPS = 'diamond_push_ups';
    case WIDE_GRIP_PUSH_UPS = 'wide_grip_push_ups';
    case DECLINE_PUSH_UPS = 'decline_push_ups';
    case CHEST_FLY = 'chest_fly';

    // 背中
    case PULL_UPS = 'pull_ups';
    case CHIN_UPS = 'chin_ups';
    case WIDE_GRIP_PULL_UPS = 'wide_grip_pull_ups';
    case NEUTRAL_GRIP_PULL_UPS = 'neutral_grip_pull_ups';
    case ASSISTED_PULL_UPS = 'assisted_pull_ups';
    case INVERTED_ROWS = 'inverted_rows';
    case LAT_PULLDOWN = 'lat_pulldown';
    case WIDE_GRIP_LAT_PULLDOWN = 'wide_grip_lat_pulldown';
    case CLOSE_GRIP_LAT_PULLDOWN = 'close_grip_lat_pulldown';
    case SEATED_CABLE_ROW = 'seated_cable_row';
    case FACE_PULLS = 'face_pulls';
    case CABLE_REVERSE_FLY = 'cable_reverse_fly';
    case STRAIGHT_ARM_PULLDOWN = 'straight_arm_pulldown';
    case REVERSE_GRIP_PULLDOWN = 'reverse_grip_pulldown';
    case BARBELL_ROW = 'barbell_row';
    case BENT_OVER_BARBELL_ROW = 'bent_over_barbell_row';
    case UNDERHAND_BARBELL_ROW = 'underhand_barbell_row';
    case T_BAR_ROW = 't_bar_row';
    case PENDLAY_ROW = 'pendlay_row';
    case DUMBBELL_ROW = 'dumbbell_row';
    case SINGLE_ARM_DUMBBELL_ROW = 'single_arm_dumbbell_row';
    case CHEST_SUPPORTED_DUMBBELL_ROW = 'chest_supported_dumbbell_row';
    case INCLINE_DUMBBELL_ROW = 'incline_dumbbell_row';
    case DUMBBELL_REVERSE_FLY = 'dumbbell_reverse_fly';
    case DUMBBELL_DEADLIFT = 'dumbbell_deadlift';
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
            self::DECLINE_BENCH_PRESS => 'デクラインベンチプレス',
            self::CLOSE_GRIP_BENCH_PRESS => 'クローズグリップベンチプレス',
            self::DUMBBELL_PRESS => 'ダンベルプレス',
            self::INCLINE_DUMBBELL_PRESS => 'インクラインダンベルプレス',
            self::DECLINE_DUMBBELL_PRESS => 'デクラインダンベルプレス',
            self::DUMBBELL_FLY => 'ダンベルフライ',
            self::INCLINE_DUMBBELL_FLY => 'インクラインダンベルフライ',
            self::DUMBBELL_PULLOVER => 'ダンベルプルオーバー',
            self::CHEST_PRESS_MACHINE => 'チェストプレスマシン',
            self::PEC_DECK_FLY => 'ペックデックフライ',
            self::CABLE_CROSSOVER => 'ケーブルクロスオーバー',
            self::CABLE_FLY_HIGH => 'ケーブルフライ（上部）',
            self::CABLE_FLY_MID => 'ケーブルフライ（中部）',
            self::CABLE_FLY_LOW => 'ケーブルフライ（下部）',
            self::PUSH_UPS => '腕立て伏せ',
            self::DIPS => 'ディップス',
            self::DIAMOND_PUSH_UPS => 'ダイヤモンドプッシュアップ',
            self::WIDE_GRIP_PUSH_UPS => 'ワイドグリッププッシュアップ',
            self::DECLINE_PUSH_UPS => 'デクラインプッシュアップ',
            self::CHEST_FLY => 'チェストフライ',
            
            self::PULL_UPS => '懸垂',
            self::CHIN_UPS => 'チンアップ',
            self::WIDE_GRIP_PULL_UPS => 'ワイドグリップ懸垂',
            self::NEUTRAL_GRIP_PULL_UPS => 'ニュートラルグリップ懸垂',
            self::ASSISTED_PULL_UPS => 'アシスト懸垂',
            self::INVERTED_ROWS => 'インバーテッドロー',
            self::LAT_PULLDOWN => 'ラットプルダウン',
            self::WIDE_GRIP_LAT_PULLDOWN => 'ワイドグリップラットプルダウン',
            self::CLOSE_GRIP_LAT_PULLDOWN => 'クローズグリップラットプルダウン',
            self::SEATED_CABLE_ROW => 'シーテッドケーブルロー',
            self::FACE_PULLS => 'フェイスプル',
            self::CABLE_REVERSE_FLY => 'ケーブルリバースフライ',
            self::STRAIGHT_ARM_PULLDOWN => 'ストレートアームプルダウン',
            self::REVERSE_GRIP_PULLDOWN => 'リバースグリップラットプルダウン',
            self::BARBELL_ROW => 'バーベルロー',
            self::BENT_OVER_BARBELL_ROW => 'ベントオーバーバーベルロー',
            self::UNDERHAND_BARBELL_ROW => 'アンダーハンドバーベルロー',
            self::T_BAR_ROW => 'Tバーロー',
            self::PENDLAY_ROW => 'ペンドレイロー',
            self::DUMBBELL_ROW => 'ダンベルロー',
            self::SINGLE_ARM_DUMBBELL_ROW => 'ワンアームダンベルロー',
            self::CHEST_SUPPORTED_DUMBBELL_ROW => 'チェストサポートダンベルロー',
            self::INCLINE_DUMBBELL_ROW => 'インクラインダンベルロー',
            self::DUMBBELL_REVERSE_FLY => 'ダンベルリバースフライ',
            self::DUMBBELL_DEADLIFT => 'ダンベルデッドリフト',
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
            self::DECLINE_BENCH_PRESS => '下部胸筋を重点的に鍛えるベンチプレス',
            self::CLOSE_GRIP_BENCH_PRESS => '狭い手幅で行う胸筋と上腕三頭筋のトレーニング',
            self::DUMBBELL_PRESS => 'ダンベルを使った胸筋トレーニング',
            self::INCLINE_DUMBBELL_PRESS => '上部胸筋を鍛えるダンベルプレス',
            self::DECLINE_DUMBBELL_PRESS => '下部胸筋を鍛えるダンベルプレス',
            self::DUMBBELL_FLY => 'ダンベルを使った胸筋のストレッチ種目',
            self::INCLINE_DUMBBELL_FLY => '上部胸筋をストレッチするフライ種目',
            self::DUMBBELL_PULLOVER => '胸筋と広背筋を同時に鍛える種目',
            self::CHEST_PRESS_MACHINE => 'マシンを使った安全な胸筋トレーニング',
            self::PEC_DECK_FLY => '胸筋を集中的に鍛えるマシン種目',
            self::CABLE_CROSSOVER => 'ケーブルを使った胸筋の収縮種目',
            self::CABLE_FLY_HIGH => '上部胸筋を狙うケーブルフライ',
            self::CABLE_FLY_MID => '中部胸筋を狙うケーブルフライ',
            self::CABLE_FLY_LOW => '下部胸筋を狙うケーブルフライ',
            self::PUSH_UPS => '自重を使った胸筋トレーニング',
            self::DIPS => '自重で胸筋下部と上腕三頭筋を鍛える',
            self::DIAMOND_PUSH_UPS => '手を菱形にして行う胸筋内側のトレーニング',
            self::WIDE_GRIP_PUSH_UPS => '広い手幅で行う胸筋外側のトレーニング',
            self::DECLINE_PUSH_UPS => '足を高くして行う上部胸筋のトレーニング',
            self::CHEST_FLY => '胸筋の分離を重視したトレーニング',
            
            self::PULL_UPS => '上半身の引く力を鍛える自重トレーニング',
            self::CHIN_UPS => '手のひらを手前に向けて行う懸垂',
            self::WIDE_GRIP_PULL_UPS => '広い手幅で行う広背筋重視の懸垂',
            self::NEUTRAL_GRIP_PULL_UPS => 'パラレルグリップで行う懸垂',
            self::ASSISTED_PULL_UPS => 'マシンの補助を使った懸垂',
            self::INVERTED_ROWS => '体を斜めにして行う自重ロー',
            self::LAT_PULLDOWN => 'マシンを使った背中のトレーニング',
            self::WIDE_GRIP_LAT_PULLDOWN => '広い手幅で行うラットプルダウン',
            self::CLOSE_GRIP_LAT_PULLDOWN => '狭い手幅で行うラットプルダウン',
            self::SEATED_CABLE_ROW => '座って行うケーブルロー',
            self::FACE_PULLS => '顔に向けて引くケーブル種目',
            self::CABLE_REVERSE_FLY => 'ケーブルを使ったリバースフライ',
            self::STRAIGHT_ARM_PULLDOWN => '腕を伸ばして行うプルダウン',
            self::REVERSE_GRIP_PULLDOWN => '逆手で行うラットプルダウン',
            self::BARBELL_ROW => 'バーベルを使った背中のトレーニング',
            self::BENT_OVER_BARBELL_ROW => '前傾姿勢で行うバーベルロー',
            self::UNDERHAND_BARBELL_ROW => 'アンダーグリップで行うバーベルロー',
            self::T_BAR_ROW => 'Tバーを使った背中のロー種目',
            self::PENDLAY_ROW => '床から引き上げるバーベルロー',
            self::DUMBBELL_ROW => 'ダンベルを使った背中のトレーニング',
            self::SINGLE_ARM_DUMBBELL_ROW => '片腕ずつ行うダンベルロー',
            self::CHEST_SUPPORTED_DUMBBELL_ROW => '胸をサポートして行うダンベルロー',
            self::INCLINE_DUMBBELL_ROW => 'インクラインベンチで行うダンベルロー',
            self::DUMBBELL_REVERSE_FLY => 'ダンベルを使ったリバースフライ',
            self::DUMBBELL_DEADLIFT => 'ダンベルを使ったデッドリフト',
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
            self::BENCH_PRESS, self::INCLINE_BENCH_PRESS, self::DECLINE_BENCH_PRESS,
            self::DUMBBELL_PRESS, self::INCLINE_DUMBBELL_PRESS, self::DECLINE_DUMBBELL_PRESS,
            self::DUMBBELL_FLY, self::INCLINE_DUMBBELL_FLY, self::CHEST_PRESS_MACHINE,
            self::PEC_DECK_FLY, self::CABLE_CROSSOVER, self::CABLE_FLY_HIGH,
            self::CABLE_FLY_MID, self::CABLE_FLY_LOW, self::PUSH_UPS,
            self::WIDE_GRIP_PUSH_UPS, self::DECLINE_PUSH_UPS, self::CHEST_FLY => [MuscleEnum::PECTORALIS_MAJOR],
            
            self::CLOSE_GRIP_BENCH_PRESS, self::DIAMOND_PUSH_UPS => [MuscleEnum::PECTORALIS_MAJOR], // 上腕三頭筋も主働筋だが現在未定義
            self::DIPS => [MuscleEnum::PECTORALIS_MAJOR], // 下部胸筋と上腕三頭筋
            self::DUMBBELL_PULLOVER => [MuscleEnum::PECTORALIS_MAJOR, MuscleEnum::LATISSIMUS_DORSI],
            
            // 背中 - 懸垂系（自重）
            self::PULL_UPS, self::WIDE_GRIP_PULL_UPS => [MuscleEnum::LATISSIMUS_DORSI],
            self::CHIN_UPS => [MuscleEnum::LATISSIMUS_DORSI], // 上腕二頭筋も関与するが現在未定義
            self::NEUTRAL_GRIP_PULL_UPS => [MuscleEnum::LATISSIMUS_DORSI],
            self::ASSISTED_PULL_UPS => [MuscleEnum::LATISSIMUS_DORSI],
            self::INVERTED_ROWS => [MuscleEnum::RHOMBOIDS, MuscleEnum::LATISSIMUS_DORSI],
            
            // 背中 - マシン・ケーブル系
            self::LAT_PULLDOWN, self::WIDE_GRIP_LAT_PULLDOWN, self::CLOSE_GRIP_LAT_PULLDOWN,
            self::REVERSE_GRIP_PULLDOWN => [MuscleEnum::LATISSIMUS_DORSI],
            self::SEATED_CABLE_ROW => [MuscleEnum::RHOMBOIDS, MuscleEnum::LATISSIMUS_DORSI],
            self::FACE_PULLS => [MuscleEnum::POSTERIOR_DELTOID, MuscleEnum::RHOMBOIDS],
            self::CABLE_REVERSE_FLY => [MuscleEnum::POSTERIOR_DELTOID, MuscleEnum::RHOMBOIDS],
            self::STRAIGHT_ARM_PULLDOWN => [MuscleEnum::LATISSIMUS_DORSI],
            
            // 背中 - バーベル系
            self::BARBELL_ROW, self::BENT_OVER_BARBELL_ROW, self::UNDERHAND_BARBELL_ROW,
            self::T_BAR_ROW, self::PENDLAY_ROW => [MuscleEnum::LATISSIMUS_DORSI, MuscleEnum::RHOMBOIDS],
            
            // 背中 - ダンベル系
            self::DUMBBELL_ROW, self::SINGLE_ARM_DUMBBELL_ROW, self::CHEST_SUPPORTED_DUMBBELL_ROW,
            self::INCLINE_DUMBBELL_ROW => [MuscleEnum::LATISSIMUS_DORSI, MuscleEnum::RHOMBOIDS],
            self::DUMBBELL_REVERSE_FLY => [MuscleEnum::POSTERIOR_DELTOID, MuscleEnum::RHOMBOIDS],
            self::DUMBBELL_DEADLIFT => [MuscleEnum::ERECTOR_SPINAE, MuscleEnum::LATISSIMUS_DORSI],
            
            self::DEADLIFT => [MuscleEnum::ERECTOR_SPINAE, MuscleEnum::GLUTES, MuscleEnum::HAMSTRINGS],
            
            self::OVERHEAD_PRESS => [MuscleEnum::ANTERIOR_DELTOID],
            self::LATERAL_RAISE => [MuscleEnum::LATERAL_DELTOID],
            self::REAR_DELT_FLY => [MuscleEnum::POSTERIOR_DELTOID],
            self::UPRIGHT_ROW => [MuscleEnum::LATERAL_DELTOID, MuscleEnum::TRAPEZIUS],
            
            self::BICEP_CURL, self::HAMMER_CURL => [],  // 腕の筋肉は現在定義されていない
            self::TRICEP_DIPS, self::TRICEP_EXTENSION => [],  // 腕の筋肉は現在定義されていない
            
            self::SQUAT, self::FRONT_SQUAT, self::LEG_PRESS => [MuscleEnum::QUADRICEPS, MuscleEnum::GLUTES],
            self::ROMANIAN_DEADLIFT => [MuscleEnum::HAMSTRINGS, MuscleEnum::GLUTES],
            self::LUNGES => [MuscleEnum::QUADRICEPS, MuscleEnum::GLUTES],
            self::CALF_RAISE => [MuscleEnum::CALVES],
            
            self::PLANK => [MuscleEnum::RECTUS_ABDOMINIS, MuscleEnum::ERECTOR_SPINAE],
            self::CRUNCHES => [MuscleEnum::RECTUS_ABDOMINIS],
            self::RUSSIAN_TWIST => [MuscleEnum::OBLIQUES],
            self::MOUNTAIN_CLIMBER => [MuscleEnum::RECTUS_ABDOMINIS, MuscleEnum::ANTERIOR_DELTOID],
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