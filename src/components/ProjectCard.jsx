import { motion } from 'motion/react';
import { Card, CardContent } from './ui/card';
import { Badge } from './ui/badge';

export function ProjectCard({ title, description, image, category, tags, onClick }) {
  return (
    <motion.div
      className="group cursor-pointer"
      whileHover={{ y: -8 }}
      whileTap={{ scale: 0.95 }}
      onClick={onClick}
    >
      <Card className="overflow-hidden border-0 shadow-lg transition-all duration-300 group-hover:shadow-xl">
        <div className="aspect-video overflow-hidden">
          <img
            src={image}
            alt={title}
            className="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
          />
        </div>
        <CardContent className="p-6">
          <div className="flex items-center justify-between mb-2">
            <Badge variant="secondary" className="text-xs">
              {category}
            </Badge>
          </div>
          <h3 className="text-lg font-semibold mb-2 group-hover:text-primary transition-colors">
            {title}
          </h3>
          <p className="text-sm text-muted-foreground mb-4 line-clamp-2">
            {description}
          </p>
          <div className="flex flex-wrap gap-1">
            {tags?.map((tag, index) => (
              <span key={index} className="text-sm text-muted-foreground tracking-wide">
                {tag}
              </span>
            ))}
          </div>
        </CardContent>
      </Card>
    </motion.div>
  );
}


