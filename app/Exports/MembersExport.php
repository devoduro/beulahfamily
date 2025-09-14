<?php

namespace App\Exports;

use App\Models\Member;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class MembersExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $members;

    public function __construct($members)
    {
        $this->members = $members;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->members;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Member ID',
            'First Name',
            'Last Name',
            'Email',
            'Phone',
            'Gender',
            'Date of Birth',
            'Age',
            'Address',
            'City',
            'State',
            'Postal Code',
            'Family Name',
            'Chapter',
            'Membership Status',
            'Membership Type',
            'Membership Date',
            'Ministries',
            'Created At'
        ];
    }

    /**
     * @param Member $member
     * @return array
     */
    public function map($member): array
    {
        $age = $member->date_of_birth ? \Carbon\Carbon::parse($member->date_of_birth)->age : '';
        $ministries = $member->ministries->pluck('name')->join(', ');

        return [
            $member->member_id,
            $member->first_name,
            $member->last_name,
            $member->email,
            $member->phone,
            ucfirst($member->gender ?? ''),
            $member->date_of_birth ? \Carbon\Carbon::parse($member->date_of_birth)->format('Y-m-d') : '',
            $age,
            $member->address,
            $member->city,
            $member->state,
            $member->postal_code,
            $member->family ? $member->family->family_name : '',
            $member->chapter ?? 'ACCRA',
            ucfirst($member->membership_status),
            ucfirst($member->membership_type),
            $member->membership_date ? \Carbon\Carbon::parse($member->membership_date)->format('Y-m-d') : '',
            $ministries,
            $member->created_at->format('Y-m-d H:i:s')
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'FFE2E8F0',
                    ],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ],
        ];
    }
}
