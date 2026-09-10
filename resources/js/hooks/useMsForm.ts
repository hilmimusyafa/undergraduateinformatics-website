import { type ApiSuccessResponse } from '../types/api';
import { type MsFormPayload } from '../types/ms-forms';
import { useSuspensePageData } from './usePageData';

export type MsFormData<TForm extends MsFormPayload = MsFormPayload> = TForm & {
    isValid: boolean;
};

const selectMsForm = <TForm extends MsFormPayload>(
    response: ApiSuccessResponse<TForm>
): MsFormData<TForm> => {
    const data = response.data;

    return {
        ...data,
        isValid: data?.link != null && (data?.questions?.length ?? 0) > 0,
    };
};

export function useMsForm<TForm extends MsFormPayload = MsFormPayload>(apiEndpoint: string) {
    return useSuspensePageData<ApiSuccessResponse<TForm>, MsFormData<TForm>>(apiEndpoint, {
        select: selectMsForm,
    });
}
