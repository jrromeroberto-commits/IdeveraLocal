import type { SVGAttributes } from 'react';

export default function AppLogoIcon(props: SVGAttributes<SVGElement>) {
    return (
        <svg
            {...props}
            viewBox="0 0 40 40"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
        >
            <path
                fillRule="evenodd"
                clipRule="evenodd"
                fill="currentColor"
                d="M5 6h12v5H10v19h7v5H5V6Zm30 0H23v5h7v19h-7v5h12V6ZM17 15h6v10h-6V15Z"
            />
        </svg>
    );
}
