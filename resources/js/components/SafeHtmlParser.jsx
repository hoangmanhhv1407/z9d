import React from 'react';
import parse, { domToReact, attributesToProps, Element } from 'html-react-parser';
import DOMPurify from 'dompurify';

const SafeHtmlParser = ({ html, className = '' }) => {
  // Làm sạch HTML đầu vào
  const cleanHtml = DOMPurify.sanitize(html, {
    USE_PROFILES: { html: true }
  });

  // Các options để tùy chỉnh parsing
  const options = {
    replace: (domNode) => {
      if (domNode instanceof Element) {
        // Xử lý các thẻ heading
        if (/^h[1-6]$/.test(domNode.name)) {
          const props = attributesToProps(domNode.attribs);
          const headingClasses = {
            h1: 'text-4xl font-bold text-gray-100 mb-6',
            h2: 'text-3xl font-bold text-gray-200 mb-5',
            h3: 'text-2xl font-bold text-gray-300 mb-4',
            h4: 'text-xl font-bold text-gray-300 mb-3',
            h5: 'text-lg font-bold text-gray-400 mb-2',
            h6: 'text-base font-bold text-gray-400 mb-2'
          };
          
          return React.createElement(
            domNode.name,
            {
              ...props,
              className: `${headingClasses[domNode.name]} ${props.className || ''}`
            },
            domToReact(domNode.children, options)
          );
        }

        // Xử lý đoạn văn
        if (domNode.name === 'p') {
          const props = attributesToProps(domNode.attribs);
          return React.createElement(
            'p',
            {
              ...props,
              className: `text-gray-300 leading-relaxed mb-4 ${props.className || ''}`
            },
            domToReact(domNode.children, options)
          );
        }

        // Xử lý links
        if (domNode.name === 'a') {
          const props = attributesToProps(domNode.attribs);
          return React.createElement(
            'a',
            {
              ...props,
              className: `text-blue-400 hover:text-blue-300 transition-colors duration-200 ${
                props.className || ''
              }`,
              target: '_blank',
              rel: 'noopener noreferrer'
            },
            domToReact(domNode.children, options)
          );
        }

        // Xử lý lists
        if (domNode.name === 'ul') {
          const props = attributesToProps(domNode.attribs);
          return React.createElement(
            'ul',
            {
              ...props,
              className: `list-disc list-inside space-y-2 mb-4 ${props.className || ''}`
            },
            domToReact(domNode.children, options)
          );
        }

        if (domNode.name === 'ol') {
          const props = attributesToProps(domNode.attribs);
          return React.createElement(
            'ol',
            {
              ...props,
              className: `list-decimal list-inside space-y-2 mb-4 ${props.className || ''}`
            },
            domToReact(domNode.children, options)
          );
        }

        // Xử lý list items
        if (domNode.name === 'li') {
          const props = attributesToProps(domNode.attribs);
          return React.createElement(
            'li',
            {
              ...props,
              className: `text-gray-300 ${props.className || ''}`
            },
            domToReact(domNode.children, options)
          );
        }

        // Xử lý images
        if (domNode.name === 'img') {
          const props = attributesToProps(domNode.attribs);
          return (
            <div className="relative w-full h-auto my-4 rounded-lg overflow-hidden">
              <img
                {...props}
                className="w-full h-auto object-cover rounded-lg"
                loading="lazy"
                alt={props.alt || 'Blog image'}
              />
            </div>
          );
        }
      }
    }
  };

  return (
    <div className={`prose prose-invert max-w-none ${className}`}>
      {parse(cleanHtml, options)}
    </div>
  );
};

export default SafeHtmlParser;