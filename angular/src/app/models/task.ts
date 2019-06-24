export class Task {
    constructor (
        public id: number,
        public title: string,
        public descrption: string,
        public status: string,
        public createdAt,
        public updatedAt
    ) {}
}