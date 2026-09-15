import api from '../../../utilities/api/apiClient';

export const userPermissionService = {

    async storePermissions(payload) {
        try {
            const response = await api.post(
                '/master/user-permissions/store',
                payload
            );
            return response.data;
        } catch (error) {
            throw error;
        }
    },

    async getPermissionsByUserId(userId) {
        try {
            const response = await api.get(
                `/master/user-permissions/${userId}`
            );

            return response.data;
        } catch (error) {
            throw error;
        }
    },

};
