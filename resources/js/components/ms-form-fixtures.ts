import { msw } from '@ladle/react';

import { type MsFormPayload } from '../types/ms-forms';

export const SUBMIT_URL = '/api/feedback';
export const SUBMIT_LABEL = 'Kirim';

export const richPayload: MsFormPayload = {
    link: 'https://forms.office.com/r/abc123',
    title: 'Form Umpan Balik',
    description: 'Bantu kami meningkatkan layanan dengan mengisi formulir di bawah ini.',
    sections: [
        {
            id: 'section-1',
            title: null,
            subtitle: 'Semua pertanyaan bertanda * wajib diisi.',
            questionIds: ['jenis', 'kategori', 'isi', 'tanggal'],
        },
    ],
    questions: [
        {
            id: 'jenis',
            title: 'Jenis Masukan',
            subtitle: null,
            type: 'choice',
            required: true,
            multiple: false,
            choices: [
                { value: 'Saran', label: 'Saran', branchTargetId: null },
                { value: 'Keluhan', label: 'Keluhan', branchTargetId: null },
                { value: 'Apresiasi', label: 'Apresiasi', branchTargetId: null },
            ],
        },
        {
            id: 'kategori',
            title: 'Aspek yang Dinilai',
            subtitle: 'Pilih semua yang relevan.',
            type: 'choice',
            required: true,
            multiple: true,
            choices: [
                { value: 'Akademik', label: 'Akademik', branchTargetId: null },
                { value: 'Administrasi', label: 'Administrasi', branchTargetId: null },
                { value: 'Fasilitas', label: 'Fasilitas', branchTargetId: null },
                { value: 'Lainnya', label: 'Lainnya', branchTargetId: null },
            ],
        },
        {
            id: 'isi',
            title: 'Isi Masukan',
            subtitle: 'Tulis masukan Anda secara singkat dan jelas.',
            type: 'text',
            required: true,
            multiple: false,
            choices: [],
        },
        {
            id: 'tanggal',
            title: 'Tanggal Pengalaman',
            subtitle: 'Kosongkan jika tidak ingin menyertakan tanggal.',
            type: 'date',
            required: false,
            multiple: false,
            choices: [],
        },
    ],
};

export const branchingPayload: MsFormPayload = {
    link: 'https://forms.office.com/r/def456',
    title: 'Form Umpan Balik',
    description: 'Pilih jenis masukan Anda untuk memulai.',
    sections: [
        {
            id: 'section-pilih',
            title: 'Jenis Masukan',
            subtitle: 'Jawaban Anda menentukan bagian yang akan diisi.',
            questionIds: ['jenis'],
        },
        {
            id: 'section-saran',
            title: 'Detail Saran',
            subtitle: 'Ceritakan saran Anda.',
            questionIds: ['saran', 'dampak'],
        },
        {
            id: 'section-keluhan',
            title: 'Detail Keluhan',
            subtitle: 'Berikan rincian agar kami dapat menindaklanjuti.',
            questionIds: ['keluhan', 'tanggal'],
        },
    ],
    questions: [
        {
            id: 'jenis',
            title: 'Jenis Masukan',
            subtitle: null,
            type: 'choice',
            required: true,
            multiple: false,
            choices: [
                { value: 'Saran', label: 'Saran', branchTargetId: 'saran' },
                { value: 'Keluhan', label: 'Keluhan', branchTargetId: 'keluhan' },
                { value: 'Apresiasi', label: 'Apresiasi', branchTargetId: 'end' },
            ],
        },
        {
            id: 'saran',
            title: 'Tuliskan Saran Anda',
            subtitle: 'Semakin rinci semakin membantu kami.',
            type: 'text',
            required: true,
            multiple: false,
            choices: [],
        },
        {
            id: 'dampak',
            title: 'Seberapa Besar Dampaknya?',
            subtitle: null,
            type: 'choice',
            required: true,
            multiple: false,
            choices: [
                { value: 'Besar', label: 'Besar', branchTargetId: 'end' },
                { value: 'Sedang', label: 'Sedang', branchTargetId: 'end' },
                { value: 'Kecil', label: 'Kecil', branchTargetId: 'end' },
            ],
        },
        {
            id: 'keluhan',
            title: 'Ceritakan Keluhan Anda',
            subtitle: null,
            type: 'text',
            required: true,
            multiple: false,
            choices: [],
        },
        {
            id: 'tanggal',
            title: 'Kapan Masalah Tersebut Terjadi?',
            subtitle: null,
            type: 'date',
            required: true,
            multiple: false,
            choices: [],
        },
    ],
};

export const simplePayload: MsFormPayload = {
    link: 'https://forms.office.com/r/ghi789',
    title: 'Form Umpan Balik',
    description: null,
    sections: [
        {
            id: 'section-1',
            title: null,
            subtitle: 'Semua pertanyaan wajib diisi.',
            questionIds: ['isi'],
        },
    ],
    questions: [
        {
            id: 'isi',
            title: 'Isi Masukan',
            subtitle: null,
            type: 'text',
            required: true,
            multiple: false,
            choices: [],
        },
    ],
};

export function toFormProps(payload: MsFormPayload) {
    return {
        title: payload.title,
        description: payload.description,
        sections: payload.sections,
        questions: payload.questions,
        submitUrl: SUBMIT_URL,
    };
}

export const submitOk = [msw.http.post(SUBMIT_URL, () => msw.HttpResponse.json({ success: true }))];

export const submitFail = [
    msw.http.post(SUBMIT_URL, () =>
        msw.HttpResponse.json(
            { message: 'Gagal mengirim formulir. Silakan coba beberapa saat lagi.' },
            { status: 422 }
        )
    ),
];
