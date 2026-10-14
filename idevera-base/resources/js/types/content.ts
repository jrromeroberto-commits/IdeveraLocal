export type ContentKind = 'SERVICE' | 'PORTFOLIO' | 'PAGE';
export type PublicMedia = {
    url: string;
    alt: string;
    width: number;
    height: number;
};
export type PublicEntry = {
    id: number;
    kind: ContentKind;
    title: string;
    slug: string;
    excerpt: string;
    body: string;
    category: string | null;
    art_style: 'orbit' | 'forma';
    is_example: boolean;
    url: string;
    media: PublicMedia[];
    seo_title?: string | null;
    seo_description?: string | null;
};
export type SiteData = {
    responsible_name?: string | null;
    contact_email?: string | null;
    contact_address?: string | null;
    provisional_legal?: boolean;
};
export type Pagination<T> = {
    data: T[];
    prev_page_url: string | null;
    next_page_url: string | null;
    current_page: number;
    last_page: number;
    total: number;
};
