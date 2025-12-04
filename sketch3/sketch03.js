const canvasSketch = require('canvas-sketch');
const math = require('canvas-sketch-util/math');
const random = require('canvas-sketch-util/random');

const settings = {
  dimensions: [1080, 1080],
  animate: true,
  fps: 12,
  // playbackRate: 'throttle'
};

const sketch = () => {
  return ({ context, width, height, time }) => {
    const t = time; // << use time instead of playhead

    context.fillStyle = 'white';
    context.fillRect(0, 0, width, height);

    const cx = width * 0.5;
    const cy = height * 0.5;

    const w = width * 0.01;
    const h = height * 0.15;

    const num = 80;
    const baseRadius = width * 0.3;

    random.setSeed(42);

    for (let i = 0; i < num; i++) {
      const slice = (Math.PI * 2) / num;
      const angle = slice * i;

      const radius =
        baseRadius +
        Math.sin(t + i * 0.2) * width * 0.05;

      const x = cx + radius * Math.sin(angle);
      const y = cy + radius * Math.cos(angle);

      // rects
      context.save();
      context.translate(x, y);
      context.rotate(-angle + Math.sin(t + i * 0.3) * 0.3);

      const scaleX = math.mapRange(
        Math.sin(t + i * 0.15),
        -1,
        1,
        0.3,
        1.8
      );
      const scaleY = math.mapRange(
        Math.cos(t + i * 0.25),
        -1,
        1,
        0.2,
        0.6
      );
      context.scale(scaleX, scaleY);

      context.fillStyle = `hsl(${math.mapRange(i, 0, num, 180, 260)}, 80%, 60%)`;
      context.beginPath();
      context.rect(-w * 0.5, -h * 0.5, w, h);
      context.fill();
      context.restore();

      // arcs
      context.save();
      context.translate(cx, cy);
      context.rotate(-angle);

      context.lineWidth = math.mapRange(
        Math.sin(t * 2 + i * 0.3),
        -1,
        1,
        3,
        20
      );

      context.strokeStyle = `hsla(${math.mapRange(i, 0, num, 200, 300)}, 80%, 40%, 0.8)`;
      context.lineCap = 'round';

      const arcRadius =
        radius *
        math.mapRange(Math.sin(t + i * 0.4), -1, 1, 0.6, 1.4);

      const arcStart = slice * math.mapRange(Math.sin(t + i), -1, 1, -4, 1);
      const arcEnd = slice * math.mapRange(Math.cos(t + i * 0.5), -1, 1, 1, 6);

      context.beginPath();
      context.arc(0, 0, arcRadius, arcStart, arcEnd);
      context.stroke();
      context.restore();
    }
  };
};

canvasSketch(sketch, settings);
