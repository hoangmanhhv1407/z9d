import React, { useRef, useEffect } from 'react';

const CanvasPlaceholder = ({ width, height, text, backgroundColor = '#4a5568', textColor = '#ffffff' }) => {
  const canvasRef = useRef(null);

  useEffect(() => {
    const canvas = canvasRef.current;
    const ctx = canvas.getContext('2d');

    // Đặt kích thước canvas
    canvas.width = width;
    canvas.height = height;

    // Vẽ nền
    ctx.fillStyle = backgroundColor;
    ctx.fillRect(0, 0, width, height);

    // Vẽ hiệu ứng đồ họa
    ctx.fillStyle = 'rgba(255, 255, 255, 0.1)';
    for (let i = 0; i < 5; i++) {
      const x = Math.random() * width;
      const y = Math.random() * height;
      const size = Math.random() * 50 + 10;
      ctx.beginPath();
      ctx.arc(x, y, size, 0, Math.PI * 2);
      ctx.fill();
    }

    // Thiết lập font và style cho text
    const fontSize = Math.min(width, height) / 10;
    ctx.font = `bold ${fontSize}px Arial, sans-serif`;
    ctx.fillStyle = textColor;
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';

    // Vẽ text
    const lines = text.split('\n');
    const lineHeight = fontSize * 1.2;
    const totalHeight = lineHeight * lines.length;
    const startY = (height - totalHeight) / 2 + lineHeight / 2;

    lines.forEach((line, index) => {
      ctx.fillText(line, width / 2, startY + index * lineHeight);
    });

    // Vẽ biểu tượng game
    const iconSize = Math.min(width, height) / 5;
    ctx.fillStyle = 'rgba(255, 255, 255, 0.2)';
    ctx.beginPath();
    ctx.moveTo(width / 2, height / 2 - iconSize);
    ctx.lineTo(width / 2 + iconSize, height / 2);
    ctx.lineTo(width / 2, height / 2 + iconSize);
    ctx.lineTo(width / 2 - iconSize, height / 2);
    ctx.closePath();
    ctx.fill();
  }, [width, height, text, backgroundColor, textColor]);

  return <canvas ref={canvasRef} className="rounded-lg shadow-lg" />;
};

export default CanvasPlaceholder;