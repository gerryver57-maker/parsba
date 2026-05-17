<?php

namespace App\Livewire\Petani\Kalender;

use Livewire\Component;
use Carbon\Carbon;

class Index extends Component
{
    public $currentDate;
    public $selectedVariety;
    public $plantingDate;

    public $varieties = [
        'IR64' => [
            'persemaian' => 7,
            'vegetatif' => 30,
            'pembungaan' => 10,
            'pengisian_gabah' => 15,
            'pemasakan' => 20,
            'total' => 82
        ],
        'Ciherang' => [
            'persemaian' => 7,
            'vegetatif' => 35,
            'pembungaan' => 12,
            'pengisian_gabah' => 18,
            'pemasakan' => 25,
            'total' => 97
        ],
        'Inpari 32' => [
            'persemaian' => 5,
            'vegetatif' => 28,
            'pembungaan' => 8,
            'pengisian_gabah' => 12,
            'pemasakan' => 15,
            'total' => 68
        ]
    ];

    public function mount()
    {
        $this->currentDate = Carbon::now();
        $this->selectedVariety = 'IR64';
        $this->plantingDate = Carbon::now()->format('Y-m-d');
    }

    // Tambahkan method baru untuk mengubah data
    public function changePlantingData()
    {
        // Contoh perubahan: 
        // - Set tanggal tanam menjadi hari ini
        // - Pilih varietas secara bergantian

        $varietyKeys = array_keys($this->varieties);
        $currentIndex = array_search($this->selectedVariety, $varietyKeys);
        $nextIndex = ($currentIndex + 0) % count($varietyKeys);

        $this->selectedVariety = $varietyKeys[$nextIndex];
        // $this->plantingDate = Carbon::now()->format('Y-m-d');

        // Atau bisa juga menggunakan contoh tanggal acak dalam 30 hari terakhir
        $this->plantingDate = Carbon::parse($this->plantingDate)->translatedFormat('Y-m-d');
    }

    public function render()
    {
        $startDate = Carbon::parse($this->plantingDate);
        $endDate = $startDate->copy()->addDays($this->varieties[$this->selectedVariety]['total']);

        $days = [];
        $currentDay = $startDate->copy();

        $phaseBoundaries = [
            'persemaian' => $this->varieties[$this->selectedVariety]['persemaian'],
            'vegetatif' => $this->varieties[$this->selectedVariety]['persemaian'] + $this->varieties[$this->selectedVariety]['vegetatif'],
            'pembungaan' => $this->varieties[$this->selectedVariety]['persemaian'] + $this->varieties[$this->selectedVariety]['vegetatif'] + $this->varieties[$this->selectedVariety]['pembungaan'],
            'pengisian_gabah' => $this->varieties[$this->selectedVariety]['persemaian'] + $this->varieties[$this->selectedVariety]['vegetatif'] + $this->varieties[$this->selectedVariety]['pembungaan'] + $this->varieties[$this->selectedVariety]['pengisian_gabah'],
            'pemasakan' => $this->varieties[$this->selectedVariety]['total']
        ];

        $dayCount = 0;
        while ($currentDay <= $endDate) {
            $phase = $this->determinePhase($dayCount, $phaseBoundaries);
            $days[] = [
                'date' => $currentDay->copy(),
                'day' => $currentDay->day,
                'month' => $currentDay->month,
                'year' => $currentDay->year,
                'phase' => $phase,
                'isToday' => $currentDay->isToday(),
                'isTomorrow' => $currentDay->isTomorrow(),
                'isDayAfterTomorrow' => $currentDay->isSameDay(Carbon::tomorrow()->addDay()),
                'dayCount' => $dayCount + 1
            ];

            $currentDay->addDay();
            $dayCount++;
        }

        return view('livewire.petani.kalender.index', [
            'days' => $days,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'phaseBoundaries' => $phaseBoundaries
        ]);
    }

    private function determinePhase($day, $boundaries)
    {
        if ($day < $boundaries['persemaian']) return 'persemaian';
        if ($day < $boundaries['vegetatif']) return 'vegetatif';
        if ($day < $boundaries['pembungaan']) return 'pembungaan';
        if ($day < $boundaries['pengisian_gabah']) return 'pengisian_gabah';
        if ($day < $boundaries['pemasakan']) return 'pemasakan';
        return 'panen';
    }

    public function getPhaseColor($phase)
    {
        $colors = [
            'persemaian' => 'table-info',
            'vegetatif' => 'table-success',
            'pembungaan' => 'table-warning',
            'pengisian_gabah' => 'table-primary',
            'pemasakan' => 'table-danger',
            'panen' => 'table-secondary'
        ];

        return $colors[$phase] ?? '';
    }

    public function getActivityRecommendation($phase, $dayCount)
    {
        $recommendations = [
            'persemaian' => [
                'Penyiapan media semai',
                'Penyemaian benih',
                'Penyiraman rutin'
            ],
            'vegetatif' => [
                'Pemupukan pertama (7-10 HST)',
                'Penyiangan gulma',
                'Pengendalian hama',
                'Pengairan berselang'
            ],
            'pembungaan' => [
                'Pemupukan kedua',
                'Pengendalian hama penggerek',
                'Pemeliharaan saluran air',
                'Pemantauan penyakit'
            ],
            'pengisian_gabah' => [
                'Pengairan cukup',
                'Pengendalian burung',
                'Pemantauan hama',
                'Penyemprotan jika diperlukan'
            ],
            'pemasakan' => [
                'Pengeringan lahan',
                'Persiapan panen',
                'Pengawasan akhir',
                'Penyemprotan terakhir jika perlu'
            ],
            'panen' => [
                'Panen ketika 90% gabah matang',
                'Pengeringan hasil panen',
                'Perontokan gabah',
                'Penyimpanan'
            ]
        ];

        $phaseRec = $recommendations[$phase] ?? [];
        return implode(', ', $phaseRec);
    }
}
