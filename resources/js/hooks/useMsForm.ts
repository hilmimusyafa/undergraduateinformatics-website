import { type ApiSuccessResponse } from '../types/api';
import { type MsFormPayload } from '../types/ms-forms';
import { usePageData } from './usePageData';

export interface MsFormData extends MsFormPayload {
    isValid: boolean;
}

const selectMsForm = (response: ApiSuccessResponse<MsFormPayload>): MsFormData => {
    const data = response.data;

    return {
        ...data,
        isValid: data?.link != null && (data?.questions?.length ?? 0) > 0,
    };
};

export function useMsForm(apiEndpoint: string) {
    return usePageData<ApiSuccessResponse<MsFormPayload>, MsFormData>(apiEndpoint, {
        select: selectMsForm,
    });
}
